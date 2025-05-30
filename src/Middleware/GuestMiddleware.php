<?php

namespace Dashed\DashedCore\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Dashed\DashedCore\Classes\AccountHelper;

class GuestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            return redirect(AccountHelper::getAccountUrl())->with('success', 'Je bent succesvol ingelogd');
        }

        return $next($request);
    }
}
