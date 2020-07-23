<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Http\Resources\ExpenseCollection;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ExpenseNotification;

class ExpenseController extends Controller
{
    public function index()
    {
        $user = request()->user();
        $expenses = Expense::with(['user'])->orderBy('created_at', 'DESC');
        if(request()->q != ''){
            $expenses = $expenses->where('title', 'LIKE', '%'.request()->q.'%');
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

        $user = $request->user(); //request user yg sedang login

        //jika user yg ngajuan expense adlah superadmin dan finance maka status langsung diset approve 
        $status = $user->role == 0 || $user->role == 2 ? 1 : 0;

        //menambahkan user_id dan status ke dalam request
        $request->request->add([
            'user_id' => $user->id,
            'status' => $status
        ]);

        $expense = Expense::create($request->all());

        $expenseNotification = new ExpenseNotification($expense, $user);

        //mengambil user superadmin dan finance. dua jenis user inilah yg akan menerima notifikasi
        $users = User::whereIn('role', [0, 2])->get();

        //mengirim notifikasinya menggunakan facade notification
        Notification::send($users, $expenseNotification);

        return response()->json(['status' => 'success']);
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

        $expense = Expense::with(['user'])->find($id);
        $expense->update($request->except('id'));

        return response()->json(['status' => 'success']);
    }

    public function destroy($id)
    {
        $expense = Expense::find($id);
        $expense->delete();

        return response()->json(['status' => 'success']);
    }

    public function accept(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:expenses,id',
        ]);

        $expense = Expense::with('user')->find($request->id);
        $expense->update(['status' => 1]); //ubah status menjadi 1. approve

        $expenseNotification = new ExpenseNotification($expense, $expense->user); 
        Notification::send($expense->user, $expenseNotification); //notifikasi dikirim ke user yg meminta expense

        return response()->json(['status' => 'success']);
    }

    public function reject(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:expenses,id',
            'reason' => 'required|string'
        ]);

        $expense = Expense::with('user')->find($request->id);

        $expense->status = 2; //ubah status menjadi 2. reject
        $expense->reason = $request->reason;
        $expense->save();

        $expenseNotification = new ExpenseNotification($expense, $expense->user); 
        Notification::send($expense->user, $expenseNotification); //notifikasi dikirim ke user yg meminta expense

        return response()->json(['status' => 'success']);
    }



    
}
 