<?php

namespace App\Repositories;

use App\Models\Note;

class NoteRepository
{
    /**
     * Creates a new note
     *
     * @param array $data
     * @return \App\Models\Note
     */
    public function create(array $data): Note
    {
        return Note::create([
            'title' => $data['title'],
            'state' => $data['state'],
            'permission' => $data['permission']
        ]);
    }
}