<?php

namespace App\Policies;

use App\Models\CollectionPoint;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CollectionPointPolicy
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
    public function update(User $user, CollectionPoint $collection_point)
    {
        return  $user->type == 'charity' || "admin";
    }
    public function delete(User $user, CollectionPoint $collection_point)
    {
        return  $user->type ==  "admin";
    }
    public function restore(User $user, CollectionPoint $collection_point)
    {
        return  $user->type ==  "admin";
    }

    public function forceDelete(User $user, CollectionPoint $collection_point)
    {
        return  $user->type ==  "admin";
    }
}
