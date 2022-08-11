<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Contracts\Auth\StatefulGuard;
class AdminAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // protected $guard;
    // public function __construct(StatefulGuard $guard)
    // {
    //     $this->guard = $guard;
    // }
    public function handle(Request $request, Closure $next)
    {
    
        if (!Auth::check())
        return redirect('/');

        $user = Auth::user();

        if($user->role === "admin" or $user->role === "admin_staff")
        {
        return $next($request);
        }
        else
        {
        Auth::logout();
       // $this->guard->logout();
        return redirect('/');
        }

    }
}
