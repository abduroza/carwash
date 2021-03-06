<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ProductType extends Model
{
    use UsesUuid;
    protected $table = ['product_type']; // to ensure table name is product_type not product_types
    protected $fillable = ['id', 'product_id', 'type_id', 'size'];

    public function product(){
        return $this->hasMany('App\Models\Product'); //belum jadi. untuk get data product yg punya size tertentu
    }
}
