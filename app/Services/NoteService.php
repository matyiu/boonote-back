<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class NoteService
{
    /**
     * Get notes from users with relationships data
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll(User $user): Collection
    {
        return $user->notes()->with('category')->with('authors')->get();
    }
}