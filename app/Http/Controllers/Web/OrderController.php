<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ScheduleRepository;
use App\Models\Schedule;

class OrderController extends Controller
{
    /**
     * The schedule repository instance.
     *
     * @var ScheduleRepository
     */
    protected $schedules;

    /**
     * Create a new controller instance.
     *
     * @param  ScheduleRepository  $schedules
     * @return void
     */
    public function __construct(ScheduleRepository $schedules)
    {
        $this->schedules = $schedules;
    }

    /**
     * Show the index order page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Only allow agent
        if (! session('weblogin')->isAgent()) {
            abort(403);
        }

        return view('inner.order.index');
    }

    /**
     * Show the download order page.
     *
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function download(Schedule $schedule)
    {
        return view('inner.order.download', ['schedule' => $this->schedules->show($schedule, [
        	'station' => 1,
        	'agent' => 1,
        ])['model']]);
    }
}
