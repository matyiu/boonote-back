<?php

namespace App\Services;

class NoteService
{
    public function getAll($user)
    {
        return $user->notes()->with('category')->get();
    }
}