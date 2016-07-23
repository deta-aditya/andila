<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repositories\AgentRepository;
use App\Repositories\SubagentRepository;
use App\Http\Controllers\Controller;
use App\Models\Subagent;

class SubagentController extends Controller
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
     * Create a new controller instance.
     *
     * @param  AgentRepository  $agents
     * @return void
     */
    public function __construct(
        AgentRepository $agents,
        SubagentRepository $subagents)
    {
        $this->agents = $agents;
        $this->subagents = $subagents;
    }

    /**
     * Show the index subagent page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('inner.subagent.index');
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
        	session()->flash('success', 'Subagen "'. Subagent::find($request->get('post'))->name .'" telah berhasil disimpan!');
        }

        if ($request->has('put')) {
            session()->flash('success', 'Subagen "'. Subagent::find($request->get('put'))->name .'" telah berhasil diubah!');
        }

        if ($request->has('delete')) {
        	session()->flash('success', 'Subagen "'. $request->get('delete') .'" telah berhasil diubah!');
        }

        return redirect()->route('web.subagents.index');
    }

    /**
     * Show the detail subagent page.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Subagent $subagent)
    {
        $data = [ 'original' => $this->subagents->show($subagent, ['agent' => 1]) ];

        $data['subagent'] = $data['original']['model'];
        $data['uri'] = url('api/v0/subagents/' . $data['subagent']['id']);

        return view('inner.subagent.show', $data);
    }

    /**
     * Show the create subagent page.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('inner.subagent.create', ['agents' => $this->agents->index([
            'fields' => 'id,name',
            'active' => true,
        ])['results']]);
    }

    /**
     * Show the edit ubagent page.
     *
     * @param  Subagent  $subagent
     * @return \Illuminate\Http\Response
     */
    public function edit(Subagent $subagent)
    {
        return view('inner.subagent.edit', ['subagent' => $subagent, 'agents' => $this->agents->index([
            'fields' => 'id,name',
            'active' => true,
        ])['results']]);
    }
}
