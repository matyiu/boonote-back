<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class AuthorService
{
    /**
     * Get all authors from the currently authenticated user
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(): Collection
    {
        $user = Auth::user();

        return $user->authors;
    }
}