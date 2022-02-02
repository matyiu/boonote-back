<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function create(array $data)
    {
        if (!is_array($data) || count($data) === 0) {
            return false;
        }

        return User::create([
            'username' => $data['username'],
            'email' => $data['email'],
            'name' => $data['name'],
            'password' => bcrypt($data['password'])
        ]);
    }
}