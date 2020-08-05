<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\OrderCollection;
use App\Models\Order;
use App\Models\Transaction;
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
            
            DB::commit();
            return response()->json(['status' => 'success', 'data' => $transactions]);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'data' => $err->getMessage()]);
        }
    }

    public function index()
    {
        $search = request()->q; //menampung queri pencarian
        $user = request()->user(); //mengambil value user yg sedang login
        $status = request()->isPaid; //menampung queri status pembayarn. berguna utk FE saat memfilter berdasarkan status pembayaran 

        //whereHas digunakan untuk menfilter pencarian di table yg berbeda
        $orders = Order::with(['user', 'customer', 'transaction'])->orderBy('created_at', 'DESC')
            ->whereHas('customer', function($q) use($search){
                $q->where('name', 'LIKE', '%'.$search.'%')
                    ->orWhere('phone', 'LIKE', '%'.$search.'%')
                    ->orWhere('address', 'LIKE', '%'.$search.'%');
            });

        //jika statusnya adalah 0 dan 1 maka ambil semuanya. 0 belum bayar, 1 sudah bayar
        // if(in_array($status, [0,1])){
        //     //maka ambil data berdasarkan status tersebut
        //     $orders = $orders->where('isPaid', $status);
        // }

        //jika rolenya bukan superadmin. Ambil hanya data dia saja
        if($user->role != 0){
            $orders = $orders->where('user_id', $user->id);
        }

        // $orders = $orders->paginate(10);

        return response()->json(['data' => $orders->get()]);
        // return new OrderCollection($orders);
    }
}
