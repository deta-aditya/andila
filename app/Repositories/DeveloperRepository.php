<?php

namespace App\Repositories;

use App\Models\Developer;
use App\Repositories\Repository;
use Carbon\Carbon;

class DeveloperRepository extends Repository
{
    /**
     * Store a new developer model in non-mass-asign mode.
     *
     * @param  string  $username
     * @param  int  $privilege
     * @return Developer
     */
    public function single($username, $privilege = 2)
    {
        $developer = new Developer([
            'username' => $username
        ]);

        $developer->privilege = $privilege;
        $developer->token_api = $this->api($fields['username'], $privilege);

        $developer->save();
        return $developer;
    }
    
    /**
     * Check whether the developer credentials for accessing the API is valid.
     *
     * @param  string  $username
     * @param  string  $token
     * @param  int  $privilege
     * @return boolean
     */
    public function canApi($username, $token, $privilege)
    {
        return Developer::where('username', $username)
            ->where('token_api', $token)
            ->where('privilege', '<=', $privilege)
            ->exists();
    }

    /**
     * Generate an API access token.
     *
     * @param  string  $username
     * @param  int  $privilege
     * @param  string|null  $timestamp
     * @return string
     */
    protected function api($username, $privilege, $timestamp = null)
    {
		$miliseconds = ! is_null($timestamp) 
			? Carbon::parse($timestamp)->format('U')
			: Carbon::now()->format('U');

		$raw = 'andila_api_'. $username .'_'. $privilege .'_'. $timestamp .'_'. str_random(4);

		return md5($raw);
    }
}
