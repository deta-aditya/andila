<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class DailyDistributionController extends Controller
{
    public function __construct()
    {
    	
    }

    public function index()
    {
    	// return view('admin.dashboard');
    }

    public function report($date)
    {
    	// return view('admin.dashboard');
    }
}
