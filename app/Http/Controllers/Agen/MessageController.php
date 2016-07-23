<?php

namespace App\Http\Controllers\Agen;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Message;

class MessageController extends Controller
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

    public function show(Message $message)
    {
    	// return view('admin.dashboard');
    }
}
