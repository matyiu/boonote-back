<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255',
            'password' => 'required|string'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Invalid data.', $validator->errors()->toArray(), 400);
        }

        $usernameFieldValidator = Validator::make($request->all(), [
            'username' => 'email'
        ]);
        $userIdField = $usernameFieldValidator->fails() ? 'username' : 'email';
        $credentials = [
            'password' => $request->get('password'),
        ];
        $credentials[$userIdField] = $request->get('username');

        if (!Auth::attempt($credentials)) {
            return $this->sendError('Username or password is incorrect.', [], 400);
        }

        $user = Auth::user();

        return $this->sendResponse([
            'token' => $user->createToken('ApiAuthToken')->plainTextToken,
        ], 'User logged in succesfully.');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users,username|string|min:4|max:255',
            'email' => 'required|unique:users,email|string|min:4|max:255|email',
            'name' => 'required|string',
            'password' => 'required|string|min:10',
            'confirm_password' => 'required|string|min:10|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Invalid data.', $validator->errors()->toArray(), 400);
        }

        $userData = $request->all();
        $user = new User;
        $user->username = $userData['username'];
        $user->email = $userData['email'];
        $user->name = $userData['name'];
        $user->password = bcrypt($userData['password']);

        if ($user->save() === false) {
            return $this->sendError('The server couldn\'t register the user.', 500);
        }

        return $this->sendResponse([
            'token' => $user->createToken('ApiAuthToken')->plainTextToken,
        ], 'User registered succesfully.');
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->sendResponse(null, 'Logged out.');
    }
}
