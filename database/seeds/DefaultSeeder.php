<?php

use DB;
use Illuminate\Database\Seeder;

class DefaultSeeder extends Seeder
{
    /**
     * Warning: As of Andila v0.2, this seed is deprecated.
     * Run the database seeds.
     * Run this seed right after installation!
     * To run, run "php artisan db:seed"
     *
     * @return void
     */
    public function run()
    {
    	DB::connection('old')->table('roles')->insert([
    		'name' => 'Administrator',
    		'slug' => 'admin'
    	], [
    		'name' => 'Agen',
    		'slug' => 'agen'
    	], [
    		'name' => 'Pangkalan',
    		'slug' => 'pangkalan'
    	]);

    	DB::connection('old')->table('users')->insert([
    		'email' => 'admin@andila.dist',
    		'password' => bcrypt('secret')
    	]);

    	DB::connection('old')->table('role_users')->insert([
    		'user_id' => 1,
    		'role_id' => 1
    	]);
    }
}
