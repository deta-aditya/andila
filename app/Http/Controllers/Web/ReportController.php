<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\ReportRepository;
use App\Repositories\StationRepository;
use App\Repositories\AgentRepository;
use App\Repositories\SubagentRepository;
use App\Repositories\ScheduleRepository;
use App\Http\Controllers\Controller;
use App\Models\Station;
use App\Models\Agent;
use App\Models\Subagent;
use App\Models\Schedule;

class ReportController extends Controller
{
    /**
     * The report repository instance.
     *
     * @var ReportRepository
     */
    protected $reports;

    /**
     * The station repository instance.
     *
     * @var StationRepository
     */
    protected $stations;

    /**
     * The agent repository instance.
     *
     * @var AgentRepository
     */
    protected $agents;

    /**
     * The subagent repository instance.
     *
     * @var SubagentRepository
     */
    protected $subagents;

    /**
     * The schedule repository instance.
     *
     * @var ScheduleRepository
     */
    protected $schedules;

    /**
     * Create a new controller instance.
     *
     * @param  ReportRepository  $reports
     * @param  StationRepository  $stations
     * @param  AgentRepository  $agents
     * @param  SubagentRepository  $subagents
     * @return void
     */
    public function __construct(
        ReportRepository $reports,
        StationRepository $stations,
        AgentRepository $agents,
        SubagentRepository $subagents,
        ScheduleRepository $schedules)
    {
        $this->reports = $reports;
        $this->stations = $stations;
        $this->agents = $agents;
        $this->subagents = $subagents;
        $this->schedules = $schedules;
    }

    /**
     * Show the index report page.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $params = ['limit' => 999];

    	if ($request->session()->get('weblogin')->isSubagent()) {
        	return view('inner.report.index');
    	} else {
    		return view('inner.report.query', [
                'stations' => $this->stations->index($params)['results'],
                'agents' => $this->agents->index($params)['results'],
                'subagents' => $this->subagents->index($params)['results'],
                'schedules' => $this->schedules->index($params)['results'],
            ]);
    	}
    }

    /**
     * Show the prequery report page.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function prequery(Request $request)
    {
        // Don't allow subagent
        if (session('weblogin')->isSubagent()) {
            abort(403);
        }

    	$data = $this->reports->index(array_merge($request->all(), [
    		'limit' => 999,
    	]));

    	return view('inner.report.result', [
    		'reports' => $data['results'],
    		'station' => $request->has('station') ? Station::find($request->station) : null,
    		'agent' => $request->has('agent') ? Agent::find($request->agent) : null,
	    	'subagent' => $request->has('subagent') ? Subagent::find($request->subagent) : null,
	    	'schedule' => $request->has('schedule') ? Schedule::find($request->schedule) : null,
	    	'range' => $request->has('range') ? explode('_', $request->range) : null,
    	]);
    }
}
