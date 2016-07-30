<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    /**
     * The user repository instance.
     *
     * @var UserRepository
     */
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @param  UserRepository  $users
     * @return void
     */
	public function __construct(UserRepository $users)
	{
        $this->users = $users;
	}

    /**
     * Perform a login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if ($request->has('errors')) {            
            $errors = json_decode($request->input('errors'), true);
            return redirect()->route('web.login')->withErrors($errors['errors']);
        }

        if ($user = $this->users->extractAccessToken($request->access_token)) {

            if ($user === -1) {
                return redirect()->route('web.login')->withErrors([
                    'access_token' => 'Access token is invalid.'
                ]);
            }

            if (! $user->isAdmin() && ! $user->handleable->active) {
                return redirect()->route('web.login')->withErrors([
                    'auth' => 'The user is not active. Please contact the administrator for activation.'
                ]);
            }

            $request->session()->put('weblogin', $user);

            return redirect()->route('web.dashboard.index');

        } else {

            return redirect()->route('web.login')->withErrors([
                'access_token' => 'The access token has expired.'
            ]);
            
        }
    }

    /**
     * Perform a logout.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        session()->forget('weblogin');

        return redirect()->route('web.home');
    }
}
