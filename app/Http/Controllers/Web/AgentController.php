<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\AgentRepository;
use App\Http\Controllers\Controller;
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
     * Create a new controller instance.
     *
     * @param  AgentRepository  $agents
     * @return void
     */
    public function __construct(AgentRepository $agents)
    {
        $this->agents = $agents;
    }

    /**
     * Show the index agent page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Only allow admin
        if (! session('weblogin')->isAdmin()) {
            abort(403);
        }

        return view('inner.agent.index');
    }

    /**
     * Process the session before going to index page.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function preindex(Request $request)
    {
        if ($request->has('post')) {
        	session()->flash('success', 'Agen "'. Agent::find($request->get('post'))->name .'" telah berhasil disimpan!');
        }

        if ($request->has('put')) {
            session()->flash('success', 'Agen "'. Agent::find($request->get('put'))->name .'" telah berhasil diubah!');
        }

        if ($request->has('delete')) {
        	session()->flash('success', 'Agen "'. $request->get('delete') .'" telah berhasil diubah!');
        }

        return redirect()->route('web.agents.index');
    }

    /**
     * Show the detail agent page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Agent $agent)
    {
        // Only allow admin and agent that has the same id
        if (session('weblogin')->isSubagent() || (session('weblogin')->isAgent() && session('weblogin')->handleable->id !== $agent->id)) {
            abort(403);
        }

        $data = [ 'original' => $this->agents->show($agent, []) ];

        $data['agent'] = $data['original']['model'];
        $data['uri'] = url('api/v0/agents/' . $data['agent']['id']);

        return view('inner.agent.show', $data);
    }

    /**
     * Show the create agent page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Only allow admin
        if (! session('weblogin')->isAdmin()) {
            abort(403);
        }

        return view('inner.agent.create');
    }

    /**
     * Show the edit agent page.
     *
     * @param  Agent  $agent
     * @return \Illuminate\Http\Response
     */
    public function edit(Agent $agent)
    {
        // Only allow admin
        if (! session('weblogin')->isAdmin()) {
            abort(403);
        }
        
        return view('inner.agent.edit', ['agent' => $agent]);
    }
}
