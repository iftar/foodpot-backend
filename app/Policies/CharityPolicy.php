<?php

namespace App\Policies;

use App\Models\Charity;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CharityPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }


    public function viewAny(User $user)
    {
        return  $user->type == 'charity' || "admin";
    }
    public function view(User $user)
    {
        return  $user->type == 'charity' || "admin";
    }
    public function create(User $user)
    {
        return  $user->type == "admin";
    }
    public function update(User $user, Charity $charity)
    {
        return  $user->type == 'charity' || "admin";
    }
    public function delete(User $user, Charity $charity)
    {
        return  $user->type ==  "admin";
    }
    public function restore(User $user, Charity $charity)
    {
        return  $user->type ==  "admin";
    }

    public function forceDelete(User $user, Charity $charity)
    {
        return  $user->type ==  "admin";
    }
}
