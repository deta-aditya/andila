<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\StationRepository;
use App\Repositories\ScheduleRepository;
use App\Models\Station;
use Carbon\Carbon;

class StationController extends Controller
{
    /**
     * The station repository instance.
     *
     * @var StationRepository
     */
    protected $stations;

    /**
     * The schedule repository instance.
     *
     * @var ScheduleRepository
     */
    protected $schedules;

    /**
     * Create a new controller instance.
     *
     * @param  StationRepository  $stations
     * @param  ScheduleRepository  $schedules
     * @return void
     */
    public function __construct(
        StationRepository $stations, 
        ScheduleRepository $schedules)
    {
        $this->stations = $stations;
        $this->schedules = $schedules;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $this->stations->valid($request->all(), 'Index');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->stations->index($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $valid = $this->stations->valid($request->all(), 'Single');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->stations->single($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Request  $request
     * @param  Station  $station
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Station $station)
    {
        $valid = $this->stations->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->stations->show($station, $request->all()), 200);
    }

    /**
     * Display schedules of the specified resource.
     *
     * @param  Request  $request
     * @param  Station  $station
     * @return \Illuminate\Http\Response
     */
    public function schedules(Request $request, Station $station)
    {
        $params = array_add($request->all(), 'station', $station->id);
        $valid = $this->schedules->valid($params, 'Index', [
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }
        
        return response()->json($this->schedules->index($params), 200);
    }

    /**
     * Store a new schedule under the specified resource.
     *
     * @param  Request  $request
     * @param  Station  $station
     * @return \Illuminate\Http\Response
     */
    public function singleSchedule(Request $request, Station $station)
    {
        $params = array_add($request->all(), 'station_id', $station->id);
        $valid = $this->schedules->valid($params, 'Single', [
            'agent_id' => $request->input('agent_id', null),
            'scheduled_date' => $request->input('scheduled_date', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->schedules->single($params), 201);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Station  $station
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Station $station)
    {
        $valid = $this->stations->valid($request->all(), 'Update');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->stations->update($station, $request->all()), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Station  $station
     * @return \Illuminate\Http\Response
     */
    public function destroy(Station $station)
    {
        return response()->json($this->stations->destroy($station), 200);
    }
}
