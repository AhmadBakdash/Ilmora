<?php

namespace App\Http\Middleware;

use App\Models\School;
use Closure;
use Illuminate\Http\Request;

class EnsureSchoolExists
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!School::exists() && !$request->is('setup*')) {
            return redirect()->route('setup');
        }
        if (School::exists() && $request->is('setup*')) {
            return redirect()->route('dashboard');
        }
        return $next($request);
    }
}
