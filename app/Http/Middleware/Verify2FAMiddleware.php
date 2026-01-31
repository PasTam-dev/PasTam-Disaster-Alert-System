<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Verify2FAMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
  public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('two-factor.index') ||
            $request->routeIs('two-factor.verify') ||
            !Auth::check()) {
            return $next($request);
        }

        if (!session()->has('two_factor_authenticated')) {
            return redirect()->route('two-factor.index');
        }

        return $next($request);
    }
}