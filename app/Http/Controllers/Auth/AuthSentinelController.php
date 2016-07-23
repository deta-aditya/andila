<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Sentinel;

class AuthSentinelController extends Controller
{
	/*
    |--------------------------------------------------------------------------
    | Sentinel Auth Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the authentication using Cartalyst/Sentinel 2.0
    |
    */

    public function __construct()
    {

    }

    public function login(Request $request)
    {
    	if($request->has('email')) 
    	{
    		$email = $request->input('email');
    	}
    	else if($request->has('id'))
    	{
    		$email = Sentinel::findById($request->input('id'))->email;
    	}
    	else
    	{
    		return redirect('/purple');
    	}

    	$credentials = [
    		'email' => $email,
    		'password' => $request->input('password')
		];

    	$response = Sentinel::authenticate($credentials);

    	if($user = Sentinel::check())
    	{
    		return redirect('/purple')->with('status', 'Login as '. $user->email .' with ID '. $user->id .'.');
    	}
    	else
    	{
    		return redirect('/purple');	
    	}
    }

    public function logout(Request $request)
    {
    	if($user = Sentinel::check())
    	{
    		Sentinel::logout($user);
    		return redirect('/purple')->with('status', 'User with ID '. $user->id .' has logged out.');	
    	}
    	else
    	{
    		return redirect('/purple')->with('status', 'No logged in user detected.');
    	}
    }

    /**
     * Store a new user.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
    	$credentials = [
    		'email' => $request->input('email'),
    		'password' => $request->input('password')
		];

    	$response = Sentinel::registerAndActivate($credentials);

    	return redirect('/purple');
    }
}
