<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Deposite;
use Carbon\Carbon;
use DB;

class OrderController extends Controller
{
    //malakukan create pada table orders dan transactions
    public function store(Request $request)
    {
        $this->validate($request, [
            'customer' => 'required',
            'transactions' => 'required',
            'transactions.*.product.id' => 'required|exists:products,id',
            'transactions.*.product.price' => 'required',
            'transactions.*.product.pivot.size' => 'required',
            'transactions.*.type_id' => 'required|exists:types,id',
            'transactions.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();
        try {
            $user = request()->user();

            $order = Order::create([
                'customer_id' => $request->customer['id'],
                'amount' => 0, //di set 0 dulu, nanti akan diupdate dibawah
                'isPaid' => 0, //di set 0 atau false saat order dibuat
                'user_id' => $user->id
            ]);

            $transactions = []; //untuk menampung data saja supaya bisa dikirim ke response. gak pakai gpp
            $amount = 0; //untuk menghitung harga total orderan
            //karena itemnya bisa lebih dari satu, maka di lakukan looping
            foreach($request->transactions as $transaction){
                if(!is_null($transaction['product'])){
                    //melakukan perhitungan subtotal
                    $subtotal = $transaction['product']['price'] * $transaction['quantity'];

                    //menyimpan data transaksi
                    $transaction = Transaction::create([
                        'order_id' => $order->id,
                        'product_id' => $transaction['product']['id'],
                        'type_id' => $transaction['type_id'],
                        'size' => $transaction['product']['pivot']['size'],
                        'quantity' => $transaction['quantity'],
                        'price' => $transaction['product']['price'],
                        'subtotal' => $subtotal, //$transaction['subtotal'],
                        'status' => 0, // 0 = process, 1 = selesai
                        'checkin' => Carbon::now(),
                        'checkout' => null
                    ]);

                    $amount += $subtotal; //mengakumulasi harga untuk tiap loopingnya
                    array_push($transactions, $transaction); //gak ada jg gak papa
                }
            }

            //mengupdate total harga
            $order->update([
                'amount' => $amount
            ]);

            $order->load('transaction'); //supaya response yg dikirim juga menyertakan table transactions. relationship transaction harus dibuat dulu di model Order.php
            
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $order], 201);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    public function view($id)
    {
        $order = Order::with(['user', 'customer.deposite', 'payment', 'transaction', 'transaction.product', 'transaction.type'])->find($id);
        return response()->json(['status' => 'success', 'data' => $order], 200);
    }

    public function makePayment(Request $request)
    {
        $this->validate($request, [
            'order_id' => 'required|exists:orders,id',
            'amount_via_cash' => 'nullable|integer',
            'amount_via_cash' => 'nullable|integer',
            'customer_change' => 'boolean'
        ]);

        DB::beginTransaction();
        try {
            $order = Order::with(['customer.deposite'])->find($request->order_id);
            $deposite = Deposite::where('customer_id', $order->customer_id)->first(); //ambil data deposite customer yg bersangkutan

            $type = $request->type;
            $typee = 0; //default set ke 0 (tipe cash)
            $paid_cash = $request->amount_via_cash; //jumlah yg dibayarkan via cash
            $paid_deposite = $request->amount_via_deposite; //jumlah yg dibayarakna via deposite
            $paid = $paid_cash + $paid_deposite; //jumlah total yg dibayarkan
            $paid_final = 0; //jika customer_change true, maka paid_final di set $paid. jika customer_change false, maka paid_final di set $bill
            $bill = $order->amount; //jumlah tagihan

            $customer_changee = 0; //set ke nol dulu, ntar diisi

            if($bill > $paid){
                return response()->json(['status' => 'errors', 'message' => 'Tagihan kurang'], 400);
            }

            //jika type bernilai true, kurangi jumlah depositnya
            if($type){
                $deposite->update([
                    'amount' => $order->customer->deposite->amount - $paid_deposite
                ]);
            }

            //cek jika ada uang kembalian, tambahkan ke deposit
            if($request->customer_change){
                $customer_changee = $paid - $bill;

                $deposite->update([
                    'amount' => $order->customer->deposite->amount + $customer_changee
                ]);
                $paid_final = $paid; //paid_final di set $paid
            } else {
                $paid_final = $bill; //paid_final di set $bill
            }

            //tipe pembayaran
            if($type && $paid_cash != 0){
                $typee = 2; //cash + deposite
            } elseif ($type && $paid_cash == 0) {
                $typee = 1; //deposite
            } else {
                $typee = 0; //cash
            }

            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $paid_final,
                'customer_change' => $customer_changee,
                'type' => $typee
            ]);

            //update isPaid jadi 1 yg artinya sudah dibayar
            $order->update([
                'isPaid' => 1 //ubah isPaid jadi 1 yg berarti sudah dibayar
            ]);

            //tambah 1 point tiap transaksi
            //Model Order memiliki relasi ke customer
            $order->customer()->update([
                'point' => $order->customer->point + 1, //point ditambah satu setiap kali order dan jika sudah bayar
            ]);

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $payment], 200);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    //update status dan checkout
    public function completeItem(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:transactions,id'
        ]);

        $transaction = Transaction::find($request->id);

        $transaction->update([
            'status' => 1, //ubah status jadi 1 (done)
            'checkout' => Carbon::now()
        ]);

        return response()->json(['status' => 'success', 'data' => $transaction], 200);
    }

    public function index()
    {
        $search = request()->q; //menampung queri pencarian
        $user = request()->user(); //mengambil value user yg sedang login
        $isPaid = request()->isPaid; //menampung queri status pembayarn. berguna utk FE saat memfilter berdasarkan status pembayaran 

        //whereHas digunakan untuk menfilter pencarian di table yg berbeda
        $orders = Order::with(['user', 'customer', 'transaction'])->orderBy('created_at', 'DESC')
            ->whereHas('customer', function($q) use($search){
                $q->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('phone', 'LIKE', '%'.$search.'%')
                    ->orWhere('address', 'LIKE', '%'.$search.'%');
            });

        // jika statusnya adalah 0 atau 1 maka ambil semuanya. 0 belum bayar, 1 sudah bayar
        if(in_array($isPaid, [0,1])){
            //maka ambil data berdasarkan status tersebut
            $orders = $orders->where('isPaid', $isPaid);
        }

        //jika rolenya bukan superadmin. Ambil hanya data dia saja
        if($user->role != 0){
            $orders = $orders->where('user_id', $user->id);
        }

        $orders = $orders->paginate(10);

        return new OrderCollection($orders);
    }
}
