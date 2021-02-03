<?php

namespace App\Services;

use App\Models\User;
use App\Events\User\Updated;
use App\Events\User\Registered;

class UserService
{
    public function exists($email)
    {
        return User::where('email', $email)->first();
    }

    public function emailTaken($email)
    {
        return User::where('id', '!=', auth()->user()->id)
                   ->where('email', $email)
                   ->first();
    }

    public function get()
    {
        /** @var User $user */
        return auth()->user();
    }

    public function create($data)
    {
        $user = User::create([
            'first_name' => $data['first_name'] ?? null,
            'last_name'  => $data['last_name'] ?? null,
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'type'       => $data['type'],
            'status'     => 'approved',
        ]);

        event(new Registered($user));

        return $user;
    }

    public function update($data)
    {
        $user = auth()->user();

        $user->update([
            'first_name' => $data['first_name'] ?? $user->first_name,
            'last_name'  => $data['last_name'] ?? $user->first_name,
            'email'      => $data['email'] ?? $user->email,
        ]);

        event(new Updated($user));

        return $user->fresh();
    }

    public function getFillable($collection)
    {
        return $collection->only(
            with((new User())->getFillable())
        );
    }
}
