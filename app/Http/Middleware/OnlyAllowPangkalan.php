<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class OnlyAllowPangkalan
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

        if ($role !== 'pangkalan') {
            return $this->landProperly($role);
        }

        return $next($request);
    }

    private function landProperly($role) 
    {
        $link = '';

        switch ($role) {

            case 'admin':
                $link = '/admin/dashboard';
                break;

            case 'agen':
                $link = '/agen/dashboard';
                break;

            default:
                $link = '/';
                break;
        }

        return redirect($link);
    }
}
