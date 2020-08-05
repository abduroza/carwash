<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductType;
use App\Http\Resources\ProductCollection;
use DB;
use Illuminate\Support\Str; //for generate uuid in pivot table

class ProductController extends Controller
{
    public function index()
    {
        //user, type adalah relasi dari eager loading yg ada di model Product
        $products = Product::with(['type','user'])->orderBy('created_at', 'DESC');
        if(request()->q != '' ){
            $products = $products->where('name', 'LIKE', '%'.request()->q.'%');
        }

        $products = $products->paginate(10);

        return new ProductCollection($products);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'type_id' => 'required|exists:types,id',
            'size' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user_id = auth()->user()->id;
            $product = Product::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'user_id' => $user_id,
            ]);

            //assign product_id, type_id and size to pivot table ('product_type')
            $product->type()->attach($request->type_id, ['id' => (string) Str::uuid(), 'size' => $request->size]);

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $product], 201);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'errors', 'data' => $err->getMessage()], 400);
        }
    }

    public function edit($id)
    {
        $product = Product::with(['type'])->find($id);

        return response()->json(['status' => 'success', 'data' => $product], 200);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|integer',
            'type_id' => 'required|exists:types,id',
            'size' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::find($id);
            $product->update($request->all());

            //update relasi many to many with pivot product_type
            $product->type()->updateExistingPivot($request->type_id, ['size' => $request->size]);

            DB::commit();
            return response()->json(['status' => 'success', 'data' => $product], 200);
        } catch (\Exception $err) {
            DB::rollback();
            return response()->json(['status' => 'failed', 'data' => $err->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        //dengan cara seperti ini saja, table pivot otomatis juga terhapus
        $product->delete();

        return response()->json(['status' => 'success']);
    }
}
