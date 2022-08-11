<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class PharmacyAuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!Auth::check())
        return redirect('/');

        $user = Auth::user();

        if($user->role === "pharmacist" or $user->role === "pharmacist_staff")
        {
        return $next($request);
        }
        else
        {
        Auth::logout();
        return redirect('/');
        }
    }
}
