<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\SubagentRepository;
use App\Models\Subagent;

class SubagentController extends Controller
{
    /**
     * The subagent repository instance.
     *
     * @var SubagentRepository
     */
    protected $subagents;

    /**
     * Create a new controller instance.
     *
     * @param  SubagentRepository  $subagents
     * @return void
     */
    public function __construct(SubagentRepository $subagents)
    {
        $this->subagents = $subagents;
    }

    /**
     * Display a listing of the resource.
     *
     $ @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $valid = $this->subagents->valid($request->all(), 'Index', [
            'contract_value' => $request->input('contract_value', null)
        ]);

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->index($request->all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function single(Request $request)
    {
        $params = $request->all();

        if (auth()->user()->isAgent()) {
            $params['agent_id'] = auth()->user()->handleable->id;
        }

        $valid = $this->subagents->valid($params, 'Single');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->single($params), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Subagent  $subagent
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Subagent $subagent)
    {
        $valid = $this->subagents->valid($request->all(), 'Show');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->show($subagent, $request->all()), 200);
    }

    /**
     * "Activate" the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Subagent  $subagent
     * @return \Illuminate\Http\Response
     */
    public function activate(Request $request, Subagent $subagent)
    {
        return response()->json($this->subagents->activate($subagent), 200);
    }

    /**
     * "Activate" multiple resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function activates(Request $request)
    {
        $valid = $this->subagents->valid($request->all(), 'Activates');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->activates($request->ids), 200);
    }

    /**
     * "Deactivate" the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Subagent  $subagent
     * @return \Illuminate\Http\Response
     */
    public function deactivate(Request $request, Subagent $subagent)
    {
        return response()->json($this->subagents->deactivate($subagent), 200);
    }

    /**
     * "Deactivate" multiple resources.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deactivates(Request $request)
    {
        $valid = $this->subagents->valid($request->all(), 'Deactivates');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->deactivates($request->ids), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Subagent  $subagent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subagent $subagent)
    {
        $valid = $this->subagents->valid($request->all(), 'Update');

        if ($valid->fails()) {
            return response()->json(['errors' => $valid->messages()], 422);
        }

        return response()->json($this->subagents->update($subagent, $request->all()), 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Subagent  $subagent
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subagent $subagent)
    {
        return response()->json($this->subagents->destroy($subagent), 200);
    }
}
