<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\UserRepository;

class AccessTokenGate
{  
    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new middleware instance.
     *
     * @param  UserRepository $users
     * @return void
     */
    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $privilege
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $request->hasHeader('X-Andila-User-Access-Token')) {
            return response()->json(['error' => 'A valid access token should present on the request header.'], 401);
        }

        $token = $request->header('X-Andila-User-Access-Token');

        if ($user = $this->users->extractAccessToken($token)) {

            if ($user === -1) {
                return response()->json(['error' => 'The requested access token is invalid.'], 401);    
            }

            auth()->login($user);
            
            // Contain the user in the service container.
            // Oh god I'm feeling so cool cause I can finally use this thing!
            app()->instance('App\Models\User', $user);

            // Set last login to the user
            $this->users->setLastLogin($user);

            return $next($request);
        } else {
            return response()->json(['error' => 'The requested access token has expired. Please re-attempt authentication.'], 400);
        }
    }

    /**
     * Terminate an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Response  $response
     * @return mixed
     */
    public function terminate($request, $response)
    {
        auth()->logout();
    }
}
