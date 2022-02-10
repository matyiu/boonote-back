<?php

namespace App\Repositories;

use App\Models\Note;
use Illuminate\Support\Facades\Auth;

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

    public function update(int $id, array $data): Note
    {
        $user = Auth::user();
        $note = $user->notes()->where('id', $id)->first();
        $note->fill($data);
        $note->save();

        return $note;
    }
}