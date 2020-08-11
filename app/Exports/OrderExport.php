<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrderExport implements FromView, ShouldAutosize
{
    protected $order;
    protected $month;
    protected $year;

    //request data order, bulan dan tahun dari DashboardController.php
    public function __construct($order, $month, $year)
    {
        $this->order = $order;
        $this->month = $month;
        $this->year = $year;
    }

    public function view(): View 
    {
        //meload view Order.blade.php dengan mengirimkan data yg direquest diatas
        return view('exports.orders', [
            'order' => $this->order,
            'month' => $this->month,
            'year' => $this->year
        ]);
    }
}
