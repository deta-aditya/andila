<?php

namespace App\Repositories;

use App\Models\Subagent;
use App\Models\Agent;
use App\Validations\SubagentValidation;
use App\Repositories\Repository;

class SubagentRepository extends Repository
{
    const CONTRACT_VALUE_MIN = 50;
    const CONTRACT_VALUE_MAX = 200;

    /**
     * The subagent validation instance.
     *
     * @var SubagentValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  SubagentValidation  $validation
     * @return void
     */
    public function __construct(SubagentValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the subagents with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        $query = $this->select(Subagent::with('user', 'address'), $params, false);

        // This is only restricted to admin LOL sorry
        if ( array_has($params, 'agent') && auth()->user()->isAdmin() ) {
            $query->ofAgent($params['agent']);
        }

        if ( array_has($params, 'contract_value') ) {
            list($operator, $value) = explode(':', $params['contract_value']);
            $query->hasContractValue($operator, $value);
        }

        if ( array_has($params, 'active') ) {
            $query->isActive($params['active']);
        }

        if ( array_has($params, 'schedulable') ) {
            ! (bool)$params['schedulable'] ?: $query->schedulable();
        }

        if (auth()->user()->isAgent()) {
            $query->ofAgent(auth()->user()->handleable->id);
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Store a new subagent model.
     *
     * @param  array  $data
     * @param  boolean  $active
     * @return array
     */
    public function single($data, $active = false)
    {
        $subagentData = array_only($data, ['name', 'email', 'phone', 'owner', 'location', 'contract_value']);

        $subagent = new Subagent($subagentData);
        $subagent->active = $active;
        $subagent->save();

        if ( array_has($data, 'address') ) {
            $subagent->address()->create($data['address']);
            $subagent->save();
            $subagent->load('address');
        }

        if ( array_has($data, 'user') ) {
            $data['user']['password'] = bcrypt($data['user']['password']);
            $subagent->user()->create($data['user']);
            $subagent->save();
            $subagent->load('user');
        }

        Agent::find($data['agent_id'])->subagents()->save($subagent);

        return $this->extractResource($subagent, 'subagents');
    }

    /**
     * Get the specified subagent model
     *
     * @param  Subagent  $subagent
     * @param  array  $params
     * @return array
     */
    public function show(Subagent $subagent, $params)
    {
        // By default it will load "address" and "user" method
        $subagent->load('address', 'user');

        if ( array_has($params, 'agent') ) {
            ! (bool)$params['agent'] ?: $subagent->load('agent'); 
        }

        if ( array_has($params, 'subschedules') ) {
            ! (bool)$params['subschedules'] ?: $subagent->load('subschedules'); 
        }

        return $this->extractResource($subagent, 'subagents');
    }

    /**
     * Activate a subagent model.
     *
     * @param  Subagent  $subagent
     * @return array
     */
    public function activate(Subagent $subagent)
    {
        $subagent->active = true;
        $subagent->save();

        return $this->extractResource($subagent, 'subagents');
    }

    /**
     * Activate multiple subagent models.
     *
     * @param  array  $ids
     * @return array
     */
    public function activates($ids)
    {
        $results = [];

        foreach ($ids as $id) {
            $results[] = $this->activate(Subagent::find($id));
        }

        return $results;
    }

    /**
     * Deactivate a subagent model.
     *
     * @param  Subagent  $subagent
     * @return array
     */
    public function deactivate(Subagent $subagent)
    {
        $subagent->active = false;
        $subagent->save();

        return $this->extractResource($subagent, 'subagents');
    }

    /**
     * Deactivate multiple subagent models.
     *
     * @param  array  $ids
     * @return array
     */
    public function deactivates($ids)
    {
        $results = [];

        foreach ($ids as $id) {
            $results[] = $this->deactivate(Subagent::find($id));
        }

        return $results;
    }

    /**
     * Update the specified subagent model.
     *
     * @param  Subagent  $subagent
     * @param  array  $params
     * @return array
     */
    public function update(Subagent $subagent, $params)
    {
        $subagentData = array_only($params, ['name', 'email', 'phone', 'owner', 'location', 'contract_value']);

        $subagent->update($subagentData);
        
        if ( array_has($params, 'address') ) {
            $subagent->address()->updateOrCreate($params['address']);
            $subagent->save();
            $subagent->load('address');
        }

        return $this->extractResource($subagent, 'subagents');
    }

    /**
     * Delete the specified subagent model.
     *
     * @param  Subagent  $subagent
     * @return array
     */
    public function destroy(Subagent $subagent)
    {
        $subagent->delete();
        $subagent->address()->delete();
        $subagent->user()->delete();
        return $this->extractResource($subagent, 'subagents');
    }
}
