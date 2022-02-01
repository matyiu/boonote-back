<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show()
    {
        $user = User::find(Auth::id());
        
        return $this->sendResponse($user->toArray(), 'User data retrieved correctly.');
    }

    public function update(Request $request)
    {
        $user = User::find(Auth::id());
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:4|max:255|unique:users,username,'.$user->id,
            'email' => 'required|string|min:4|max:255|email|unique:users,email,'.$user->id,
            'name' => 'required|string',
            'description' => 'string|nullable',
            'password' => 'string|min:10|nullable',
            'confirm_password' => 'string|min:10|same:password|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Invalid Data.', $validator->errors()->toArray(), 400);
        }

        $data = $request->all();
        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->description = $this->selectDataToSave('description', $request);
        $user->email = $data['email'];
        $user->password = $this->_isPasswordEmpty($request) ? $user->password : bcrypt($data['password']);
        $user->profile_pic = $this->selectDataToSave('profile_pic', $request);

        if (!$user->save()) {
            return $this->sendError('User data couldn\'t be updated.', [], 500);
        }

        return $this->sendResponse(null, 'User data updated correctly.');
    }

    private function selectDataToSave($key, $request)
    {
        $user = User::find(Auth::id())->toArray();

        if (!$request->has($key)) {
            return $user[$key];
        }

        return $request->input($key);
    }

    private function _isPasswordEmpty($request)
    {
        $data = $request->all();

        return !isset($data['password']);
    }
}
