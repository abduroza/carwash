<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Product extends Model
{
    use UsesUuid;

    protected $fillable = ['id', 'name', 'description', 'price', 'user_id' ];
    // protected $guarded = [];

    //membuat relasi many to many dengan type
    public function type(){
        return $this->belongsToMany(Type::class, 'product_type', 'product_id', 'type_id')->withPivot(['size'])->withTimestamps();
    }

    //membuat relasi one to many dengan user
    public function user(){
        return $this->belongsTo(User::class);
    }
    
}
