<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        // Role_id=1 (admin)
        if(Auth::check() && Auth::user()->role_id==1){
            return $next($request);
        }
        // Role_id=2 (Executive)
        if(Auth::check() && Auth::user()->role_id == 2){
            return \redirect()->route('executive');
        }
        // Role_id = 3 (Developer)
        if(Auth::check() && Auth::user()->role_id == 3 ){
            return redirect()->route('developer');
        }
    }
}
