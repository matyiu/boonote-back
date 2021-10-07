<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $user = User::find(Auth::id());
        $note = $user->notes()->where('id', $id)->first();

        if (!$note) {
            return $this->sendError('Note couldn\'t be found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Invalid Data.', $validator->errors()->toArray(), 400);
        }

        $tag = new Tag;
        $tag->name = $request->get('name');

        if (!$tag->save()) {
            return $this->sendError('Tag couldn\'t be created.', [], 500);
        } elseif (!$user->tags()->save($tag)) {
            $tag->delete();

            return $this->sendError('Tag couldn\'t be assigned to user.', [], 500);
        }

        $messages = ['Tag stored correctly.'];
        if (!$note->tags()->save($tag)) {
            $messages[] = 'Warning: tag couldn\'t be assigned to note.';
        }

        return $this->sendResponse(null, $messages);
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
        $user = User::find(Auth::id());
        $tag = $user->tags()->where('id', $id)->first();

        if (!$tag) {
            return $this->sendError('Tag couldn\'t be found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Invalid Data.', $validator->errors()->toArray(), 400);
        }

        $tag->name = $request->get('name');

        if (!$tag->save()) {
            return $this->sendError('Tag couldn\'t be updated.', [], 500);
        }

        return $this->sendResponse(null, 'Tag updated correctly.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find(Auth::id());
        $tag = $user->tags()->where('id', $id)->first();

        if (!$tag) {
            return $this->sendError('Tag couldn\'t be found.');
        }

        if (!$tag->delete()) {
            return $this->sendError('Tag couldn\'t be deleted.', [], 500);
        }

        return $this->sendResponse(null, 'Tag deleted correctly.');
    }
}
