<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Type;

class TypeController extends Controller
{
    public function index()
    {
        //get semua type diurutkan berdasarkan name
        $types = Type::orderBy('name', 'ASC')->get();

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

        return response()->json(['status' => 'success', 'data' => $type], 201);
    }
}
