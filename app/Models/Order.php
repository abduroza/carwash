<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Order extends Model
{
    use UsesUuid;
    
    protected $guarded = [];

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
