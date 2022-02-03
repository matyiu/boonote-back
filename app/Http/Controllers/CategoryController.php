<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService
    )
    {}

    /**
     * Get all categories from current authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->categoryService->getAll();

        return $this->sendResponse($categories, 'Categories retrieved correctly');
    }

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

        $tag = new Category;
        $tag->name = $request->get('name');

        if (!$tag->save()) {
            return $this->sendError('Category couldn\'t be created.', [], 500);
        } elseif (!$user->tags()->save($tag)) {
            $tag->delete();

            return $this->sendError('Category couldn\'t be assigned to user.', [], 500);
        }

        $messages = ['Category stored correctly.'];
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
            return $this->sendError('Category couldn\'t be found.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|required|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Invalid Data.', $validator->errors()->toArray(), 400);
        }

        $tag->name = $request->get('name');

        if (!$tag->save()) {
            return $this->sendError('Category couldn\'t be updated.', [], 500);
        }

        return $this->sendResponse(null, 'Category updated correctly.');
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
            return $this->sendError('Category couldn\'t be found.');
        }

        if (!$tag->delete()) {
            return $this->sendError('Category couldn\'t be deleted.', [], 500);
        }

        return $this->sendResponse(null, 'Category deleted correctly.');
    }
}
