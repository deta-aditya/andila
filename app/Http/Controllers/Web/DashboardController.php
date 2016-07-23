<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\StationRepository;
use App\Repositories\AgentRepository;
use App\Repositories\SubagentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\OrderRepository;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
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
     * The order repository instance.
     *
     * @var OrderRepository
     */
    protected $orders;

    /**
     * Create a new controller instance.
     *
     * @param  StationRepository  $stations
     * @param  AgentRepository  $agents
     * @param  SubagentRepository  $subagents
     * @param  ScheduleRepository  $schedules
     * @param  OrderRepository  $orders
     * @return void
     */
    public function __construct(
        StationRepository $stations,
        AgentRepository $agents,
        SubagentRepository $subagents,
        ScheduleRepository $schedules,
        OrderRepository $orders)
    {
        $this->stations = $stations;
        $this->agents = $agents;
        $this->subagents = $subagents;
        $this->schedules = $schedules;
        $this->orders = $orders;
    }
    /**
     * Show the dashboard page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    	$data = [];

    	switch (session('weblogin')->handling) {

    		case 'Administrator': 

    			$data = [
    				'stationCount' => $this->stations->index(['count' => true])['count'],
    				'agentCount' => $this->agents->index(['count' => true])['count'],
    				'subagentCount' => $this->subagents->index(['count' => true])['count'],
    				'orderCount' => $this->orders->index(['accepted' => true, 'count' => true])['count'],
    				'pendingOrders' => $this->orders->index(['accepted' => false, 'sort' => 'created_at:asc'])['results'],
    				'pendingSubagents' => $this->subagents->index(['active' => false, 'sort' => 'created_at:asc'])['results'],
    			];

    			break;

    		case 'Agent': 

    			$data = [
    				'subagentCount' => $this->subagents->index(['count' => true, 'agent' => session('weblogin')->handleable->id])['count'],
    				'orderCount' => $this->orders->index(['accepted' => true, 'agent' => session('weblogin')->handleable->id, 'count' => true])['count'],
    				'pendingSchedules' => $this->schedules->index(['ordered' => false, 'sort' => 'scheduled_date:asc'])['results'],
    			];

    			break;

    		case 'Subagent':

    			return redirect()->route('web.reports.index');

    	}

        return view('inner.dashboard', $data);

    }
}
