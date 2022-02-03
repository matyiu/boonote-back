<?php

namespace App\Services;

use App\Models\Note;
use App\Models\User;
use App\Repositories\NoteRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class NoteService
{
    public function __construct(
        private NoteRepository $noteRepository
    )
    {}

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

    /**
     * Create note and create relationship with authenticated user
     *
     * @param array $data
     * @return Note
     */
    public function create(array $data): Note|false
    {
        $note = $this->noteRepository->create($data);
        $loggedUser = Auth::user();

        if (!$loggedUser->notes()->save($note)) {
            $note->delete();
            
            return false;
        }

        return $note;
    }
}