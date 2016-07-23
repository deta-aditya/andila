<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\AgentRepository;
use App\Repositories\SubagentRepository;
use App\Repositories\ScheduleRepository;
use App\Repositories\SubscheduleRepository;
use App\Models\Agent;

class AgentController extends Controller
{
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
     * The subschedule repository instance.
     *
     * @var SubscheduleRepository
     */
    protected $subschedules;

    /**
     * Create a new controller instance.
     *
     * @param  AgentRepository  $agents
     * @param  SubagentRepository  $subagents
     * @param  ScheduleRepository  $schedules
     * @param  SubscheduleRepository  $subschedules
     * @return void
     */
    public function __construct(
        AgentRepository $agents, 
        SubagentRepository $subagents, 
        ScheduleRepository $schedules,
        SubscheduleRepository $subschedules)
    {
        $this->agents = $agents;
        $this->subagents = $subagents;
        $this->schedules = $schedules;
        $this->subschedules = $subschedules;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $valid = $this->agents->valid($request->all(), 'Index');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->agents->index($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $valid = $this->agents->valid($request->all(), 'Single');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->agents->single($request->all()), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Agent $agent)
    {
        $valid = $this->agents->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->agents->show($agent, $request->all()), 200);
    }

    /**
     * Display subagents of the specified resource.
     *
     * @param  Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function subagents(Request $request, Agent $agent)
    {
        $params = array_add($request->all(), 'agent', $agent->id);
        $valid = $this->subagents->valid($params, 'Index', [
            'contract_value' => $request->input('contract_value', null)
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->index($params), 200);
    }

    /**
     * Display schedules of the specified resource.
     *
     * @param  Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function schedules(Request $request, Agent $agent)
    {
        $params = array_add($request->all(), 'agent', $agent->id);
        $valid = $this->schedules->valid($params, 'Index', [
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }
        
        return response()->json($this->schedules->index($params), 200);
    }

    /**
     * Display subschedules of the specified resource.
     *
     * @param  Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function subschedules(Request $request, Agent $agent)
    {
        $params = array_add($request->all(), 'agent', $agent->id);
        $valid = $this->subschedules->valid($params, 'Index', [
            'range' => $request->input('range', null),
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subschedules->index($request->all()), 200);
    }

    /**
     * Store a new subagent under the specified resource.
     *
     * @param  Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function singleSubagent(Request $request, Agent $agent)
    {
        $params = array_add($request->all(), 'agent_id', $agent->id);
        $valid = $this->subagents->valid($params, 'Single');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->single($params), 201);
    }

    /**
     * "Activate" the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, Agent $agent)
    {
        return response()->json($this->agents->activate($agent), 200);
    }

    /**
     * "Activate" multiple resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activates(Request $request)
    {
        $valid = $this->agents->valid($request->all(), 'Activates');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->agents->activates($request->ids), 200);
    }

    /**
     * "Deactivate" the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, Agent $agent)
    {
        return response()->json($this->agents->deactivate($agent), 200);
    }

    /**
     * "Deactivate" multiple resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deactivates(Request $request)
    {
        $valid = $this->agents->valid($request->all(), 'Deactivates');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->agents->deactivates($request->ids), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agent $agent)
    {
        $valid = $this->agents->valid($request->all(), 'Update');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->agents->update($agent, $request->all()), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agent $agent)
    {
        return response()->json($this->agents->destroy($agent), 200);
    }
}
