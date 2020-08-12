<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Deposite;
use App\Http\Resources\CustomerCollection;
use DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with(['deposite'])->orderBy('name', 'ASC');
        if(request()->q != ''){
            $customers = $customers->where('nik', 'LIKE', '%'.request()->q.'%')
                                ->orWhere('name', 'LIKE', '%'.request()->q.'%')
                                ->orWhere('address', 'LIKE', '%'.request()->q.'%')
                                ->orWhere('phone', 'LIKE', '%'.request()->q.'%');
        }
        $customers = $customers->paginate(8);

        return new CustomerCollection($customers);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nik' => 'nullable|unique:customers,nik|max:16',
            'name' => 'required|string|min:3|max:150',
            'address' => 'required|string',
            'phone' => 'required|max:13|unique:customers,phone',
            'amount' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::create([
                'nik' => $request->nik,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
                'point' => 0 //point langsung di set 0. semua point dimulai 0
            ]);
    
            //create pd table deposites
            $deposite = Deposite::create([
                'amount' => $request->amount,
                'customer_id' => $customer->id
            ]);

            $customer->load('deposite'); //supaya response yg dikirim juga memyertakan table deposite. relationship harus dibuat dulu

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $customer], 201);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        $customer = Customer::with(['deposite'])->find($id);

        return response()->json(['status' => 'success', 'data' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'nik' => 'nullable|max:16',
            'name' => 'required|string|min:3|max:150',
            'address' => 'required|string',
            'phone' => 'required|max:13',
            'amount' => 'required|numeric',
        ]);

        DB::beginTransaction();
        try {
            $customer = Customer::find($id);

            $customer->update([
                'nik' => $request->nik,
                'name' => $request->name,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            //search id deposite menggunakan relasinya. di model Customer.php ada fungsi/relasi deposite()
            $deposite = Deposite::find($customer->deposite->id);
            $deposite->amount = $request->amount;
            $deposite->save();

            DB::commit();
            return response()->json(['status' => 'success'], 200);
        } catch (\Exceptiom $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'message' => $err->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        //table depositenya otomatis terhapus
        $customer->delete();

        return response()->json(['status' => 'success']);
    }
}
