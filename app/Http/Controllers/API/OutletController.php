<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Http\Resources\OutletCollection;

class OutletController extends Controller
{
    public function index()
    {
        // mengambil data outlets yang telah di-sortir berdasarkan created_at dengan urutan teratas adalah data terbaru
        $outlets = Outlet::orderBy('created_at', 'DESC');
        // apabila q tidak kosong atau ada parameter pencarian yang dikirimkan, maka kita menambahkan query untuk mem-filter data berdasarkan name dari outlets
        if(request()->q != ''){
            $outlets = $outlets->where('name', 'LIKE', '%'.request()->q.'%')
                                ->orWhere('address', 'LIKE', '%'.request()->q.'%')
                                ->orWhere('phone', 'LIKE', '%'.request()->q.'%'); //gak perlu diberi ->get()
        }
        // menggunakan API resources dari Laravel untuk men-format data dari hasil query yang telah dilakukan, dimana data yang diambil hanya 10 data dalam sekali query menggunakan fungsi paginate()
        $outlets = $outlets->paginate(5);
        return new OutletCollection($outlets);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:outlets,code',
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|max:13'
        ]);

        $outlet = Outlet::create($request->all());
        
        return response()->json(['status' => 'success', 'data' => $outlet], 200); //data outlet sebenarnya tidak dikirim jg gak papa. karena gak dibutuhin FE
    }

    public function edit($id)
    {
        $outlet = Outlet::find($id); //atau pakai Outlet::whereCode($id)->first();

        return response()->json(['status' => 'success', 'data' => $outlet], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required|exists:outlets,code',
            'name' => 'required|string|max:100',
            'address' => 'required|string',
            'phone' => 'required|max:13'
        ]);

        $outlet = Outlet::find($id);
        $outlet->update($request->except('code')); //kode tidak diizinkan untuk diubah

        return response()->json(['status' => 'success', 'data' => $outlet], 200); //data sebenarnya tidak dikirim jg gak papa. karena gak dibutuhin FE
    }

    public function destroy($id)
    {
        $outlet = Outlet::find($id);
        $outlet->delete();

        return response()->json(['status' => 'success', 'data' => $outlet], 200);
    }

    //get all outlet without pagination
    public function indexNoPage()
    {
        $outlets = Outlet::orderBy('created_at', 'DESC');

        if(request()->q != ''){
            $outlets = $outlets->where('name', 'LIKE', '%'.request()->q.'%')
                                ->orWhere('address', 'LIKE', '%'.request()->q.'%')
                                ->orWhere('phone', 'LIKE', '%'.request()->q.'%'); 
        }
        $outlets = $outlets->get();
        return response()->json(['status' => 'success', 'data' => $outlets], 200);
    }
}
