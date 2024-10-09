<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCustomer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $role = Auth::user()->role;
        if ($role != UserRole::CUSTOMER->value) {
            abort(403, "You don't have customer access.");
        }
        return $next($request);
    }
}