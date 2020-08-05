<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Customer extends Model
{
    use UsesUuid;

    protected $guarded = [];

    //relasi one to one dengan table deposite
    public function deposite(){
        return $this->hasOne('App\Models\Deposite');
    }

    public function order(){
        return $this->hasMany('App\Models\Order');
    }
}
