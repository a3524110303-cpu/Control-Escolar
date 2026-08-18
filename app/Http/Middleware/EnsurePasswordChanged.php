<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->primer_ingreso && ! $request->routeIs('password.first.*', 'logout')) {
            return redirect()->route('password.first.edit');
        }

        return $next($request);
    }
}
