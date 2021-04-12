<?php

namespace App\Policies;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
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
        return  $user->type ==  "admin";
    }
    public function view(User $user)
    {
        return  $user->type == "admin";
    }
    public function create(User $user)
    {
        return  $user->type == "admin";
    }
    public function update(User $user, Tag $tag)
    {
        return  $user->type == "admin";
    }
    public function delete(User $user, Tag $tag)
    {
        return  $user->type ==  "admin";
    }
    public function restore(User $user, Tag $tag)
    {
        return  $user->type ==  "admin";
    }

    public function forceDelete(User $user, Tag $tag)
    {
        return  $user->type ==  "admin";
    }
}
