<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Order extends Model
{
    use UsesUuid;
    
    protected $guarded = [];
    protected $appends = ['status_badge']; //akan membuat field baru yg bernama status_badge di response order

    //ini adalah assesor untuk custom field yg akan di append di json
    public function getStatusBadgeAttribute(){
        //jika di table orders field isPaid bernilai 1 dan di table transaction field status bernilai 0 
        if($this->isPaid == 1 && $this->transaction[0]->status == 0){
            return '<span class="badge badge-primary">Lunas</span>';
        } elseif ($this->isPaid == 1 && $this->transaction[0]->status == 1) { //jika di table orders field isPaid bernilai 1 dan di table transaction field status bernilai 1
            return '<span class="badge badge-success">Selesai</span>';
        } else {
            return '<span class="badge badge-warning">Process</span>';
        }
    }

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }

    public function transaction(){
        return $this->hasMany('App\Models\Transaction');
    }

    public function payment(){
        return $this->hasOne('App\Models\Payment');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

}
