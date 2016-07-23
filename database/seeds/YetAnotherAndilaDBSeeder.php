<?php

use App\Models\Subagent;
use App\Models\Address;
use App\Models\User;
use Illuminate\Database\Seeder;

class YetAnotherAndilaDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create subagents
    	// Along with address and user for each of them
        factory(Subagent::class, 5)->create()->each(function ($subagent) {
 	       	$subagent->address()->save(factory(Address::class)->make());
 	       	$subagent->user()->save(factory(User::class)->make());
        });
    }
}
