<?php

use App\Models\Station;
use App\Models\Agent;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnotherAndilaDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Create stations
    	// And address for each of them
        factory(Station::class, 5)->create()->each(function ($station) {
 	       	$station->address()->save(factory(Address::class)->make());
        });

    	// Create agents
    	// Along with address and user for each of them
        factory(Agent::class, 5)->create()->each(function ($agent) {
 	       	$agent->address()->save(factory(Address::class)->make());
 	       	$agent->user()->save(factory(User::class)->make());
        });
        
        // Create subagents
        // Along with address and user for each of them
        factory(Subagent::class, 5)->create()->each(function ($subagent) {
            $subagent->address()->save(factory(Address::class)->make());
            $subagent->user()->save(factory(User::class)->make());
        });
    }
}
