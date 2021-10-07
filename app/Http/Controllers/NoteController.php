<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $notes = $user->notes;

        return $this->sendResponse($notes->toArray(), 'Notes retrieved correctly.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $validator = Validator::make($request->all(), [
            'note' => 'string|nullable',
            'rate' => 'integer|nullable|numeric|gte:0|lte:10',
            'state' => 'integer|required|numeric|between:0,5',
            'cover' => 'nullable|url|string|max:255',
            'permission' => 'required|integer|numeric|between:0,2',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid Data.', $validator->errors()->toArray(), 400);
        }

        $data = $request->all();
        $note = new Note;
        $note->title = $data['title'];
        $note->note = $data['note'] ?? null;
        $note->rate = $data['rate'] ?? 0;
        $note->state = $data['state'];
        $note->cover = $data['cover'] ?? null;
        $note->permission = $data['permission'];

        if (!$note->save()) {
            return $this->sendError('Note couldn\'t be created.', null, 500);
        }

        if (!$user->notes()->save($note)) {
            $note->delete();

            return $this->sendError('Note couldn\'t be created.', null, 500);
        }

        return $this->sendResponse(null, 'Note stored correctly.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail(Auth::id());
        $note = $user->notes()->where('id', $id)->first();
        if (!$note) {
            return $this->sendError('Note couldn\'t be found.');
        }

        return $this->sendResponse($note, 'Note retrieved correctly.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail(Auth::id());
        $note = $user->notes()->where('id', $id)->first();
        if (!$note) {
            return $this->sendError('Note couldn\'t be found.');
        }

        $validator = Validator::make($request->all(), [
            'note' => 'string|nullable',
            'rate' => 'integer|nullable|numeric|gte:0|lte:10',
            'state' => 'integer|required|numeric|between:0,5',
            'cover' => 'nullable|url|string|max:255',
            'permission' => 'required|integer|numeric|between:0,2',
            'title' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid Data.', $validator->errors()->toArray(), 400);
        }

        $data = $request->all();
        $note->title = $data['title'];
        $note->note = $data['note'] ?? null;
        $note->rate = $data['rate'] ?? 0;
        $note->state = $data['state'];
        $note->cover = $data['cover'] ?? null;
        $note->permission = $data['permission'];

        if (!$note->save()) {
            return $this->sendError('Note for user "' . $user->username . ' couldn\'t be updated.');
        }

        return $this->sendResponse(null, 'Note updated correctly.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail(Auth::id());
        $whereNote = $user->notes()->where('id', $id);
        $note = $whereNote->first();
        if (!$note) {
            return $this->sendError('Note for user couldn\'t be found.');
        }

        $whereNote->delete();
        return $this->sendResponse(null, 'Note deleted succesfully');
    }

    public function detachTag($id, $tagId)
    {
        $user = User::findOrFail(Auth::id());
        $note = $user->notes()->where('id', $id)->first();

        if (!$note) {
            return $this->sendError('Note couldn\'t be found.');
        }

        $note->tags()->detach($tagId);

        return $this->sendResponse(null, 'Tag detached correctly.');
    }

    public function attachTag($id, $tagId)
    {
        $user = User::findOrFail(Auth::id());
        $note = $user->notes()->where('id', $id)->first();

        if (!$note) {
            return $this->sendError('Note couldn\'t be found.');
        }

        if ($note->tags()->where('tag_id', $tagId)->first()) {
            return $this->sendResponse(null, 'Tag already attached to note.');
        }

        if (!$user->tags()->where('id', $tagId)->first()) {
            return $this->sendError('Tag couldn\'t be found.');
        }

        $note->tags()->attach($tagId);

        return $this->sendResponse(null, 'Tag attached correctly.');
    }
}
