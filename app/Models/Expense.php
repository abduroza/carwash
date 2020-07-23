<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Expense extends Model
{
    use UsesUuid;
    protected $guarded = [];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
