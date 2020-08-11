<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use DB;

class DashboardController extends Controller
{
    public function chart()
    {
        $filter = request()->year . '-' . request()->month; //request data bulan dan tahun. contoh output "2020-07"

        $parse = Carbon::parse($filter); //ubah formatnya jadi format carbon. cth "2020-08-31T23:59:59.999999Z"
        //membuat range tanggal dari tanggal 1 - 31 atau menyesuaikan jumlah hari dalm satu bulan
        $array_date = range($parse->startOfMonth()->format('d'), $parse->endOfMonth()->format('d'));

        //get data transaksi berdasarkan bulan dan tanggal yg diminta, kemudian kelompokkan berdasrkan tanggalnya. sum data amount kemudian simpan ke nama baru yaitu total
        // date(created_at) as date berarti mengambil date di field created_at. sum(amount) as total berarti menjumlahkan field amount dalam satu hari
        $orders = Order::select(DB::raw('date(created_at) as date, sum(amount) as total'))
                    ->where('created_at', 'LIKE', '%'.$filter.'%')
                    ->groupBy(DB::raw('date(created_at)'))->get();

        $data = [];
        //looping tanggal bulan saat ini
        foreach($array_date as $row){
            //cek tanggalnya, jika hanya 1 angka (1-9) maka tambahkan angka 0 didepannya. misal 01, 02, 03 dst
            $f_date = strlen($row) == 1 ? 0 . $row : $row;

            $datee = $filter . '-' . $f_date; //menambahkan tanggal. yg awalnya "2020-07" sehingga jadi "2020-07-02"

            //cari data date berdasrkan $datee pada collection hasil query
            $totall = $orders->firstWhere('date', $datee);

            //simpan data ke variable data
            $data[] = [
                'date' => $datee,
                'total' => $totall ? $totall->total : 0 //$total->total berarti mengambil dari "sum(amount) as total". akan menampilkan jumlah total omset selama sehari
            ];
        }

        return $data;
    }
}
