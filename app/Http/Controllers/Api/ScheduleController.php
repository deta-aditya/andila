<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\ScheduleRepository;
use App\Repositories\OrderRepository;
use App\Repositories\SubscheduleRepository;
use App\Models\Schedule;

class ScheduleController extends Controller
{
    /**
     * The schedule repository instance.
     *
     * @var ScheduleRepository
     */
    protected $schedules;

    /**
     * The order repository instance.
     *
     * @var OrderRepository
     */
    protected $orders;

    /**
     * The subschedule repository instance.
     *
     * @var SubscheduleRepository
     */
    protected $subschedules;

    /**
     * Create a new controller instance.
     *
     * @param  ScheduleRepository  $schedules
     * @param  OrderRepository  $orders
     * @param  SubscheduleRepository  $subschedules
     * @return void
     */
    public function __construct(
        ScheduleRepository $schedules, 
        OrderRepository $orders, 
        SubscheduleRepository $subschedules)
    {
        $this->schedules = $schedules;
        $this->orders = $orders;
        $this->subschedules = $subschedules;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $this->schedules->valid($request->all(), 'Index', [
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->schedules->index($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $valid = $this->schedules->valid($request->all(), 'Single', [
            'agent_id' => $request->input('agent_id', null),
            'scheduled_date' => $request->input('scheduled_date', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->schedules->single($request->all()), 201);
    }

    /**
     * Bulk store multiple resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function multiple(Request $request)
    {
    	foreach ($request->all() as $single) {
	        $valid = $this->schedules->valid($single, 'Single', [
                'agent_id' => $request->input('agent_id', null),
                'scheduled_date' => $request->input('scheduled_date', null),
            ]);

            if ($valid->fails()) {
                return response()->json(['errors' => $valid->messages()], 422);
            }
	    }

 	   return response()->json($this->schedules->multiple($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Schedule $schedule)
    {
        $valid = $this->schedules->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->schedules->show($schedule, $request->all()), 200);
    }

    /**
     * Display subschedules of the specified resource.
     *
     * @param  Request  $request
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function subschedules(Request $request, Schedule $schedule)
    {
        $params = array_add($request->all(), 'schedule', $schedule->id);
        $valid = $this->subschedules->valid($params, 'Index', [
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subschedules->index($request->all()), 200);
    }

    /**
     * Store a new order under the specified resource.
     *
     * @param  Request  $request
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function singleOrder(Request $request, Schedule $schedule)
    {
        $params = array_add($request->all(), 'schedule_id', $schedule->id);
        $valid = $this->orders->valid($params, 'Single', [
            'schedule_id' => $schedule->id,
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->orders->single($params), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Schedule  $schedule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Schedule $schedule)
    {
        $valid = $this->schedules->valid($request->all(), 'Destroy', [
            'schedule' => $schedule,
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->schedules->destroy($schedule), 200);
    }
}
