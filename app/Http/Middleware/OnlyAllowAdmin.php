<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class OnlyAllowAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Sentinel::getUser();
        $role = $user->roles[0]->slug;

        if ($role !== 'admin') {
            return $this->landProperly($role);
        }

        return $next($request);
    }

    private function landProperly($role) 
    {
        $link = '';

        switch ($role) {

            case 'agen':
                $link = '/agen/dashboard';
                break;

            case 'pangkalan':
                $link = '/pangkalan/dashboard';
                break;

            default:
                $link = '/';
                break;
        }

        return redirect($link);
    }
}
