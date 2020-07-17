<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('user');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $email = $request->get('email');
        $password = $request->get('password');

        if (Auth::attempt([
            'email' => $email,
            'password' => $password
        ])) {
            $request->session()->regenerate();

            return response()->json('', 204);
        } else {
            return response()->json([
                'error' => 'invalid_credentials'
            ], 403);
        }
    }

    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return response()->json('', 204);
    }
}
