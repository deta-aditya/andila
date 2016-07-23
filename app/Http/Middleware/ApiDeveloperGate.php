<?php

namespace App\Http\Middleware;

use Closure;
use App\Repositories\DeveloperRepository;

class ApiDeveloperGate
{   
    /**
     * The developer repository instance.
     *
     * @var DeveloperRepository
     */
    protected $developers;

    /**
     * Create a new middleware instance.
     *
     * @param  DeveloperRepository $developers
     * @return void
     */
    public function __construct(DeveloperRepository $developers)
    {
        $this->developers = $developers;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $privilege
     * @return mixed
     */
    public function handle($request, Closure $next, $privilege = 2)
    {
        if (! $request->hasHeader('X-Andila-Developer-Username') || ! $request->hasHeader('X-Andila-Developer-Api-Token')) {
            return response()->json(['error' => 'A valid pair of developer username and API token should be present on the request header.'], 401);
        }

        if (($request->isMethod('POST') || $request->isMethod('PUT')) && ! $request->isJson()) {
            return response()->json(['error' => 'The MIME type of the request body should be an application/json.'], 406);
        }

        $username = $request->header('X-Andila-Developer-Username');
        $token = $request->header('X-Andila-Developer-Api-Token');

        if (! $this->developers->canApi($username, $token, $privilege)) {
            return response()->json(['error' => 'The requested developer credentials do not have an authority to access this resource or are invalid.'], 403);
        }

        return $next($request);
    }
}
