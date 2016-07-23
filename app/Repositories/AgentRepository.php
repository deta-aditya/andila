<?php

namespace App\Repositories;

use App\Models\Agent;
use App\Validations\AgentValidation;
use App\Repositories\Repository;

class AgentRepository extends Repository
{
    /**
     * The agent validation instance.
     *
     * @var AgentValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  AgentValidation  $validation
     * @return void
     */
    public function __construct(AgentValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the agents with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        $query = $this->select(Agent::with('user', 'address'), $params, false);

        if ( array_has($params, 'active') ) {
            $query->isActive($params['active']);
        }

        if ( array_has($params, 'schedulable') ) {
            ! (bool)$params['schedulable'] ?: $query->schedulable();
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Store a new agent model.
     *
     * @param  array  $data
     * @param  bool  $active
     * @return array
     */
    public function single($data, $active = false)
    {
        $agentData = array_only($data, ['name', 'email', 'phone', 'owner', 'location']);

        $agent = new Agent($agentData);
        $agent->active = $active;
        $agent->save();

        if ( array_has($data, 'address') ) {
            $agent->address()->create($data['address']);
            $agent->save();
            $agent->load('address');
        }

        if ( array_has($data, 'user') ) {
            $data['user']['password'] = bcrypt($data['user']['password']);
            $agent->user()->create($data['user']);
            $agent->save();
            $agent->load('user');
        }

        return $this->extractResource($agent, 'agents');
    }

    /**
     * Get the specified agent model
     *
     * @param  Agent  $agent
     * @param  array  $params
     * @return array
     */
    public function show(Agent $agent, $params)
    {
        // By default it will load "address" and "user" method
        $agent->load('address', 'user');

        if ( array_has($params, 'subagents') ) {
            ! (bool)$params['subagents'] ?: $agent->load('subagents'); 
        }

        if ( array_has($params, 'schedules') ) {
            ! (bool)$params['schedules'] ?: $agent->load('schedules'); 
        }

        if ( array_has($params, 'subschedules') ) {
            ! (bool)$params['subschedules'] ?: $agent->load('subschedules'); 
        }

        if ( array_has($params, 'stations') ) {
            ! (bool)$params['stations'] ?: $agent->load('stations'); 
        }

        return $this->extractResource($agent, 'agents');
    }

    /**
     * Activate a agent model.
     *
     * @param  Agent  $agent
     * @return array
     */
    public function activate(Agent $agent)
    {
        $agent->active = true;
        $agent->save();

        return $this->extractResource($agent, 'agents');
    }

    /**
     * Activate multiple agent models.
     *
     * @param  array  $ids
     * @return array
     */
    public function activates($ids)
    {
        $results = [];

        foreach ($ids as $id) {
            $results[] = $this->activate(Agent::find($id));
        }

        return $results;
    }

    /**
     * Deactivate a agent model.
     *
     * @param  Agent  $agent
     * @return array
     */
    public function deactivate(Agent $agent)
    {
        $agent->active = false;
        $agent->save();

        return $this->extractResource($agent, 'agents');
    }

    /**
     * Deactivate multiple agent models.
     *
     * @param  array  $ids
     * @return array
     */
    public function deactivates($ids)
    {
        $results = [];

        foreach ($ids as $id) {
            $results[] = $this->deactivate(Agent::find($id));
        }

        return $results;
    }

    /**
     * Update the specified agent model.
     *
     * @param  Agent  $agent
     * @param  array  $params
     * @return array
     */
    public function update(Agent $agent, $params)
    {
        $agentData = array_only($params, ['name', 'email', 'phone', 'owner', 'location']);

        $agent->update($agentData);
        
        if ( array_has($params, 'address') ) {
            $agent->address()->updateOrCreate($params['address']);
            $agent->save();
            $agent->load('address');
        }

        return $this->extractResource($agent, 'agents');
    }

    /**
     * Delete the specified agent model.
     *
     * @param  Agent  $agent
     * @return array
     */
    public function destroy(Agent $agent)
    {
        $agent->delete();
        $agent->address()->delete();
        $agent->user()->delete();
        return $this->extractResource($agent, 'agents');
    }
}
