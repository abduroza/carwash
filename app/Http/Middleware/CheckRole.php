<?php

namespace App\Http\Middleware;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles) //roles pakai ... supaya bisa mengecek lebih dari 1 yaitu bentuknya array
    {
        //in_array karena role yg mau dicek bisa lebih dari satu. role yg di cek ada di routes/api.php
        if(in_array($request->user(), $roles)){
            return $next($request);
        }
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
