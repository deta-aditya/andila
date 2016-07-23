<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
    	
    }

    public function index()
    {
    	// return view('admin.dashboard');
    }

    public function create()
    {
    	// return view('admin.dashboard');
    }

    public function show(Usser $user)
    {
    	// return view('admin.dashboard');
    }

    public function edit(User $user)
    {
    	// return view('admin.dashboard');
    }
}
