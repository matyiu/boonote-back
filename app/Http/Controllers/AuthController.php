<?php

namespace App\Http\Controllers;

use App\Http\Requests\LogInRequest;
use App\Http\Requests\SignUpRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService
    )
    {}

    public function login(LogInRequest $request)
    {
        $data = $request->validated();

        if (Auth::attempt($data) || Auth::attempt([
            'email' => $data['username'],
            'password' => $data['password']
        ])) {
            $request->session()->regenerate();

            return $this->sendResponse(Auth::user(), 'User logged in succesfully.');
        }

        return $this->sendError('Username or password is incorrect.', [], 400);
    }

    public function register(SignUpRequest $request)
    {
        $data = $request->validated();
        $user = $this->userService->create($data);

        if ($user === false) {
            return $this->sendError('The server couldn\'t register the user.', 500);
        }

        return $this->sendResponse(null, 'User registered succesfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->sendResponse(null, 'Logged out.');
    }
}
