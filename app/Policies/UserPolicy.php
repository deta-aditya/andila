<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine if the user can access index method.
     *
     * @param  User  $user
     * @return bool
     */
    public function index(User $user)
    {
        return $user->isAdmin();
    }
    
    /**
     * Determine if the user can access admin method.
     *
     * @param  User  $user
     * @return bool
     */
    public function admin(User $user)
    {
        return $user->isAdmin();
    }
    
    /**
     * Determine if the user can access show method.
     *
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function show(User $user, User $target)
    {
        return $user->isAdmin() || $user->id === $target->id;
    }
    
    /**
     * Determine if the user can access inbox method.
     *
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function inbox(User $user, User $target)
    {
        return $user->id === $target->id;
    }
    
    /**
     * Determine if the user can access outbox method.
     *
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function outbox(User $user, User $target)
    {
        return $user->id === $target->id;
    }
    
    /**
     * Determine if the user can access draftbox method.
     *
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function draftbox(User $user, User $target)
    {
        return $user->id === $target->id;
    }
    
    /**
     * Determine if the user can access update method.
     *
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function update(User $user, User $target)
    {
        return $user->isAdmin() || $user->id === $target->id;
    }
    
    /**
     * Determine if the user can access destroy method.
     *
     * @param  User  $user
     * @param  User  $target
     * @return bool
     */
    public function destroy(User $user, User $target)
    {
        return $user->isAdmin() && $target->isAdmin() && $user->id !== $target->id;
    }
}
