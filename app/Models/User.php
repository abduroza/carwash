<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\UsesUuid;

class User extends Authenticatable
{
    use Notifiable;
    use UsesUuid;

    protected $guarded = []; //allow all field to save data

    public function outlet(){
        return $this->belongsTo(Outlet::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }

    //membuat local scope. Fungsi ini untuk mengambil data dimana role = 3. Karena role 3 adalah user dengan tipe operator
    public function scopeOperator($query){
        return $query->where('role', 3);
    }

    //membuat accessor. supaya nama user dimulai dengan huruf kapital
    public function getNameAttribute($value)
    {
        return ucfirst($value);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'name', 'email', 'password',
    // ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', //'api_token'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
