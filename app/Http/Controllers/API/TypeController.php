<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;
use App\Models\ProductType;

class TypeController extends Controller
{
    public function index()
    {
        //get semua type diurutkan berdasarkan name
        $types = Type::with(['product'])->orderBy('name', 'ASC')->get();

        return response()->json(['status' => 'success', 'data' => $types], 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name_type' => 'required|unique:types,name'
        ]);

        $type = Type::create([
            'name' => $request->name_type
        ]);

        return response()->json(['status' => 'Success', 'data' => $type, 'message' => 'Berhasil menambahkan customer baru'], 201);
    }

    //get product berdasarkan id type dan size. sehingga didapatkan data product. kelemahannya: ketika get size, datanya tidak mengandung type sebagaimana ketika get type saja tanpa get size
    public function showTypeForTransaction(Request $request,$id)
    {
        $size = request()->size;

        if($size == ''){
            //menampilkan type beserta product yg berelasi dg type yg diinputkan
            $types = Type::with(['product'])->find($id);
        } else {
            //menampilkan daftar product yg dimiliki oleh type dg size yg diinputkan. ini yg dipakai
            $types = Type::find($id)->product()->where('size', $size)->get();
        }

        return response()->json(['status' => 'success', 'data' => $types]);
    }

    //sementara ini gak jadi dipakai. pusing
    public function showProductForTrx(Request $request, $id)
    {
        $type = Type::find($id);
       
        $productTypes = ProductType::where('type_id', $type->id); //->get();
        // dd('$productTypes->get()');
        $productId = $productTypes->pluck('product_id');

        //ngambil produc
        $products = Product::whereIn($productTypes)->get();

        return response()->json(['status' => 'success', 'data' => $products]);
    }
}
