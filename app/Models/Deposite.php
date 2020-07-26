<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Deposite extends Model
{
    use UsesUuid;

    protected $guarded = [];

    public function customer(){
        return $this->belongsTo('App\Models\Customer');
    }
}
