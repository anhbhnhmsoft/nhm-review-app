<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, $role, $guard = 'web')
    {   
        if (!Auth::guard($guard)->check() || Auth::guard($guard)->user()->role != (int)$role && $guard == 'web') {
            return redirect(route('frontend.login'));
        } else if (!Auth::guard($guard)->check() || Auth::guard($guard)->user()->role != (int)$role && $guard == 'admin') {
            return redirect(route('dashboard') . 'admin');
        }

        return $next($request);
    }
}
