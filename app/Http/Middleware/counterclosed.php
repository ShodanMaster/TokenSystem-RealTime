<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CounterClosed
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::guard('counter')->check()){
            return redirect()->route('login');
        }

        $counter = Auth::guard('counter')->user();

        if($counter->closed){
            return redirect()->route('closedcounter');
        }
        return $next($request);
    }
}
