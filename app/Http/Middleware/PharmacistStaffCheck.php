<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;
class PharmacistStaffCheck
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
        if(Auth::user()->role == 'pharmacist_staff')
        {
            return redirect()->route('pharmacist.patient.index');
            // return redirect()->route('pharmacist.dashboard');
        }
        return $next($request);
    }
}
