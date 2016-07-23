<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\SubscheduleRepository;

class SubscheduleController extends Controller
{
    /**
     * The subschedule repository instance.
     *
     * @var SubscheduleRepository
     */
    protected $subschedules;

    /**
     * Create a new controller instance.
     *
     * @param  SubscheduleRepository  $subschedules
     * @return void
     */
    public function __construct(SubscheduleRepository $subschedules)
    {
        $this->subschedules = $subschedules;
    }

    /**
     * Show the index subschedule page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inner.subschedule.index');
    }
}
