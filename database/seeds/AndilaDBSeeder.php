<?php

use Illuminate\Database\Seeder;

class AndilaDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a super developer
        DB::connection('andila')->table('developers')->insert([
            'username' => 'super',
            'privilege' => 0,
            'token_api' => md5('andila_api_super_0_'. time() .'_'. str_random(4))
        ]);

        // Create an administrator user
        DB::connection('andila')->table('users')->insert([
            'email' => 'admin@andila.dist',
            'password' => bcrypt('secret')
        ]);
    }
}
