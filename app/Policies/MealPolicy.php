<?php

namespace App\Policies;

use App\Models\Meal;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MealPolicy
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
        return  $user->type == "charity" || "admin";
    }
    public function view(User $user)
    {
        return  $user->type == "charity" || "admin";
    }
    public function create(User $user)
    {
        return  $user->type == "admin" || "charity" ;
    }
    public function update(User $user, Meal $meal)
    {
        return  $user->type == "charity" || "admin";
    }
    public function delete(User $user, Meal $meal)
    {
        return  $user->type ==  "admin";
    }
    public function restore(User $user, Meal $meal)
    {
        return  $user->type ==  "admin";
    }

    public function forceDelete(User $user, Meal $meal)
    {
        return  $user->type ==  "admin";
    }
    public function attachAnyTag(User $user, Meal $meal)
    {
        return  $user->type ==  "admin";
    }
}
