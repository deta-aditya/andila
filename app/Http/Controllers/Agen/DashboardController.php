<?php

namespace App\Http\Controllers\Agen;

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
    	return view('agen.dashboard');
    }

    public function help()
    {
    	// return view('admin.dashboard');
    }
}
