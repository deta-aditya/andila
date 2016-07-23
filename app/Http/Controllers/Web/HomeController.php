<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the default page.
     *
     * @return \Illuminate\Http\Response
     */
    public function root()
    {
        if (session()->has('weblogin')) {
            return redirect()->route('web.dashboard.index');
        } else {
            return redirect()->route('web.home');
        }
    }

    /**
     * Show the landing page.
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view('welcome')->with([
            'bodyClass' => 'bs19-body-welcome'
        ]);
    }

    /**
     * Show the login page.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('login')->with([
            'bodyClass' => 'bs19-body-login',
            'layout' => 'login-page'
        ]);
    }

    /**
     * Show the about page.
     *
     * @return \Illuminate\Http\Response
     */
    public function about()
    {
        return view('about', [
            'bodyClass' => 'bs19-body-welcome'
        ]);
    }

    /**
     * Show the learn more page.
     *
     * @return \Illuminate\Http\Response
     */
    public function learnMore()
    {
        // return view('learnmore');
    }
}
