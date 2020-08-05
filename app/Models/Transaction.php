<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Transaction extends Model
{
    use UsesUuid;

    protected $guarded = [];
    
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
