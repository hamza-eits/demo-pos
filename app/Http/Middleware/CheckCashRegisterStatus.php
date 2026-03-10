<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\CashRegister;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCashRegisterStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $user_id = Auth::id();
        $openedCashRegister = CashRegister::where('user_id', $user_id)
            ->where('status', 1)
            ->first();

        if (!$openedCashRegister) {
            return redirect()->route('cash-register.create');
        }

        $request->merge(['cash_register_id' => $openedCashRegister->id]);


        return $next($request);
    }
}
