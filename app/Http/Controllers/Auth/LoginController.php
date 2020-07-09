<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function login (Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email', //jika input user error, maka akan menampilkan message sesuai kesalahan, termasuk email sudah terdaftar atau belum. tp di postman tidak terdetek
            'password' => 'required'
        ]);
        
        //ambil semua request (email, password) kecuali remmeber_me
        $auth = $request->except(['remember_me']);
        
        //melakukan otentikasi
        if(auth()->attempt($auth, $request->remember_me)){
            //apabila berhasil generate api_token menggunakan str random
            auth()->user()->update(['api_token' => Str::random(40)]);

            //kirim response ke client utk diproses lebih lanjut
            return response()->json(['status' => 'success', 'data' => auth()->user()], 200);
        }

        return response()->json(['status' => 'failed', 'message' => 'Email atau password salah']);
    }
}
