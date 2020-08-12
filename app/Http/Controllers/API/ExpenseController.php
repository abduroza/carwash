<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Http\Resources\ExpenseCollection;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExpenseNotification;
use DB;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $expenses = Expense::with(['user'])->orderBy('created_at', 'DESC');
        $input = request()->q;
        if($input != ''){
            $expenses = $expenses->where('title', 'LIKE', '%'.$input.'%')
                                //menfilter di table user
                                ->orWhereHas('user', function($query) use ($input) {
                                return $query->where('name', 'LIKE', '%'.$input.'%');
                                });
        }

        if(in_array($user->role, [1,3])){
            $expenses = $expenses->where('user_id', $user->id);
        }

        $expenses = $expenses->paginate(5);

        return new ExpenseCollection($expenses);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:300',
            'price' => 'required|integer',
            'note' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user(); //request user yg sedang login

            //jika user yg ngajuan expense adlah superadmin dan finance maka status langsung diset approve 
            $status = $user->role == 0 || $user->role == 2 ? 1 : 0;

            $expense = Expense::create([
                'title' => $request->title,
                'price' => $request->price,
                'note' => $request->note,
                'user_id' => $user->id,
                'status' => $status
            ]);

            $expenseNotification = new ExpenseNotification($expense, $user);

            //mengambil user superadmin dan finance. dua jenis user inilah yg akan menerima notifikasi
            $users = User::whereIn('role', [0, 2])->get();

            //mengirim notifikasinya menggunakan facade notification
            Notification::send($users, $expenseNotification);

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Berhasil membuat expense baru'], 201);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    public function edit($id)
    {
        $expense = Expense::with(['user'])->find($id);

        return response()->json(['status' => 'success', 'data' => $expense]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|string|max:300',
            'price' => 'required|integer',
            'note' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::with(['user'])->find($id); //juga memanggil eager loading user. karena edit ini dipakai juga oleh View.Vue untuk menampilkan nama user
            //update dg cara mendefinisikan field2nya lebih aman. sehingga hanya field yg didefiniskan yg akan diproses. FE lebih menyukai
            $expense->update([
                'title' => $request->title,
                'price' => $request->price,
                'note' => $request->note
            ]); 
            
            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Berhasil mengupdate expense']);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();

        return response()->json(['status' => 'Success', 'message' => 'Berhasil menghapus expense']);
    }

    public function accept(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:expenses,id',
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::with('user')->find($request->id);
            $expense->update(['status' => 1]); //ubah status menjadi 1. approve

            $expenseNotification = new ExpenseNotification($expense, $expense->user); 
            Notification::send($expense->user, $expenseNotification); //notifikasi dikirim ke user yg meminta expense

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Berhasil mengapprove expense']);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    public function reject(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:expenses,id',
            'reason' => 'required|string'
        ]);

        DB::beginTransaction();
        try {
            $expense = Expense::with('user')->find($request->id);

            $expense->status = 2; //ubah status menjadi 2. reject
            $expense->reason = $request->reason;
            $expense->save();

            $expenseNotification = new ExpenseNotification($expense, $expense->user); 
            Notification::send($expense->user, $expenseNotification); //notifikasi dikirim ke user yg meminta expense

            DB::commit();
            return response()->json(['status' => 'Success', 'message' => 'Berhasil menolak expense']);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }
}
 