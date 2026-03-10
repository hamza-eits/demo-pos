<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;


        // dd("RedirectIfAuthenticated");

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                
                if(Auth::user()->type == "super-admin"){
                    return redirect()->route('admin-dashboard');
                }
        
                elseif(Auth::user()->type == "admin"){
                    return redirect()->route('admin-dashboard');
                }
                elseif(Auth::user()->type == "user"){
                    return redirect()->route('point-of-sale.create');
                }
        
                else{
                    if (Auth::check()) {
                        Auth::logout();
                    }
                    return redirect()->route('login');
                }
            }
        }

        return $next($request);
    }
}
