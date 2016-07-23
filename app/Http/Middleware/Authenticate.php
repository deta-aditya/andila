<?php

namespace App\Http\Middleware;

use Closure;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (! $request->session()->has('weblogin')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('web.login')->withErrors(['login' => 'Anda tidak memiliki hak untuk mengakses halaman tadi.']);
            }
        }

        return $next($request);
    }
}
