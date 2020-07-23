<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $user = request()->user(); //ambil user yg sedang login
        //yg diread adalah notifikasi yg statusnya belum di read. yg sudah diread diamkan
        //dapat diakses secara langsung dengan mengakses notifications
        $unread = $user->unreadNotifications;

        return response()->json(['status' => 'success', 'data' => $unread]);
    }

    //method store untuk menandai suatu notification yg sudah dibaca. memberi value pada read_at
    public function store(Request $request)
    {
        $user = request()->user();

        //edit record notifikasi berdasarkan id yg diterima
        //notifikasi yg belum di read ditndai dengan read_at yg masih null
        $user->unreadNotifications()->where('id', $request->id)->update(['read_at' => now()]);
        return response()->json(['status' => 'success']);
    }
}
