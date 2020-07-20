<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Type extends Model
{
    use UsesUuid;

    protected $guarded = [];

    public function product(){
        return $this->belongsToMany(Product::class, 'product_type', 'type_id', 'product_id')->withPivot(['size'])->withTimestamps();
    }
}
