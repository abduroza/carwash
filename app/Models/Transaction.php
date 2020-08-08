<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Transaction extends Model
{
    use UsesUuid;

    protected $guarded = [];
    protected $dates = ['checkin', 'checkout']; //ini harus ada, jika gak ada error
    protected $appends = ['status_label', 'time']; //supaya attribute baru ini muncul di json. Contoh StatusLabel menjadi status_label. kata get dan Attribute dibuang

    //membuat attribute/field baru yaitu status_label
    public function getStatusLabelAttribute(){
        //$this diarahkan ke field yg akan dituju
        if($this->status == 1){
            return '<span class="badge badge-success">Selesai</span>';
        }
        return '<span class="badge badge-warning">Process</span>';
    }

    public function getTimeAttribute(){
        //melakukan format ulang waktu sesuai waktu indonesia dg menambahkan format() pada tanggal yg ingin diubah
        if($this->checkout){ //jika checkout tidak null
            return $this->checkin->format('d-m-Y H:i') . '  s/d  ' . $this->checkout->format('d-m-Y H:i');
        }
        return $this->checkin->format('d-m-Y H:i');
    }
    
    public function order(){
        return $this->belongsTo('App\Models\Order');
    }

    public function product(){
        return $this->belongsTo('App\Models\Product');
    }

    public function type(){
        return $this->belongsTo('App\Models\Type');
    }
}
