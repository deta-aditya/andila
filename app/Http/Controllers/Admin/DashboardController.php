<?php

namespace App\Http\Controllers\Admin;

use Sentinel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
    	
    }

    public function index()
    {
    	return view('admin.dashboard');
    }

    public function help()
    {
    	// return view('admin.dashboard');
    }

    public function settings()
    {
    	// return view('admin.dashboard');
    }
}
