<?php

namespace App\Repositories;

use Auth;
use Carbon\Carbon;
use App\Repositories\Repository;
use App\Validations\UserValidation;
use App\Models\User;

class UserRepository extends Repository
{
    /**
     * The user validation instance.
     *
     * @var UserValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  UserValidation  $validation
     * @return void
     */
    public function __construct(UserValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the users with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        return $this->select(User::query(), $params);
    }

    /**
     * Store a new administrator user model.
     *
     * @param  array  $data
     * @return array
     */
    public function admin($data)
    {
        $user = new User($data);        
        $user->password = bcrypt($data['password']);
        $user->save();

        return $this->extractResource($user, 'users');
    }

    /**
     * Check whether the credentials are valid for authentication.
     *
     * @param  array  $credentials
     * @return array
     */
    public function canAuth($credentials)
    {
        return ['valid' => Auth::once($credentials)];
    }

    /**
     * Set last login property to user
     *
     * @param  User  $user
     * @return array
     */
    public function setLastLogin(User $user)
    {
        $user->last_login = Carbon::now()->format('Y-m-d H:i:s');
        $user->save();

        return $this->extractResource($user, 'users');
    }

    /**
     * Get the specified user model
     *
     * @param  User  $user
     * @param  array  $params
     * @return array
     */
    public function show(User $user, $params)
    {
        if ( array_has($params, 'handleable') ) {
            ! (bool)$params['handleable'] ?: $user->load('handleable'); 
        }

        if ( array_has($params, 'messages') ) {
            ! (bool)$params['messages'] ?: $user->load('inbox', 'outbox'); 
        }

        if ( array_has($params, 'conversations') ) {
            ! (bool)$params['conversations'] ?: $user->load('conversations'); 
        }

        if ( array_has($params, 'attachments') ) {
            ! (bool)$params['attachments'] ?: $user->load('attachments'); 
        }

        return $this->extractResource($user, 'users');
    }

    /**
     * Update the specified user model.
     *
     * @param  User  $user
     * @param  array  $params
     * @return array
     */
    public function update(User $user, $params)
    {
        array_forget($params, ['password_old', 'password_confirmation']);

        if (array_has($params, 'password')) {
            $params['password'] = bcrypt($params['password']);
        }

        $this->isUserOP($user) ?: $user->update($params);
        return $this->extractResource($user, 'users');
    }

    /**
     * Delete the specified user model.
     *
     * @param  User  $user
     * @return array
     */
    public function destroy(User $user)
    {
        $this->isUserOP($user) ?: $user->delete();
        return $this->extractResource($user, 'users');
    }

    /**
     * Get the access token from user model.
     *
     * @param  User  $user
     * @return array
     */
    public function getAccessToken(User $user)
    {
        return $this->insertToken($user);
    }

    /**
     * Extract access token into a user model. Returns false if expired, and returns -1 if invalid.
     *
     * @param  string  $token
     * @return User|int|bool
     */
    public function extractAccessToken($token)
    {
        $extraction = $this->extractToken($token);

        if (count($extraction) !== 4) {
            return -1;
        }

        list($trademark, $version, $id, $timestamp) = $extraction;

        if ($this->checkTimestampExpiry($timestamp)) {
            return false;
        }

        return User::findOrFail($id);
    }

    /**
     * Insert the user token for access token encryption.
     *
     * @param  User  $user
     * @return array
     */
    private function insertToken(User $user)
    {
        $now = Carbon::now();
        $expires = $now->copy()->addMinutes($this->ACCESS_SECRET_EXPIRY);

        return [
            'token' => $this->encrypt(implode('_', ['andila', $this->ACCESS_SECRET_VER, $user->id, $now->format('U')])), 
            'expires' => $expires->timestamp,
        ];
    }

    /**
     * Extract the user "spell" for access token encryption.
     *
     * @param  string  $string
     * @return array
     */
    private function extractToken($string)
    {
        return explode('_', $this->decrypt($string));
    }

    /**
     * Check whether the requested user is OP PLZ NERF.
     * LOL, OP here means "the unmodifyable" user which was seeded to
     * the database once Andila was deployed. There's no way that user
     * is either edited or removed. Right?
     *
     * @param  User  $user
     * @return bool
     */
    private function isUserOP(User $user)
    {
        return $user->email === 'admin@andila.dist';
    }
}
