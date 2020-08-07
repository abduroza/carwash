<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Payment extends Model
{
    use UsesUuid;
    
    protected $guarded = [];

    public function order(){
        return $this->belongsTo('App\Models\Order');
    }
}
