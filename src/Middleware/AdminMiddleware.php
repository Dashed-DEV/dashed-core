<?php

namespace Dashed\DashedCore\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect('/'.config('filament.path').'/login')->with('error', 'Je moet ingelogd zijn om deze pagina te bezoeken');
        }

        if (Auth::user()->role != 'admin') {
            return redirect('/')->with('error', 'Je moet ingelogd zijn om deze pagina te bezoeken');
        }

        return $next($request);
    }
}
