<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        // Try to authenticate with the api guard
        if (!auth('api')->attempt($credentials)) {
            return response()->json(['message'=>'Invalid credentials'], 401);
        }

        // Get the authenticated user
        $user = auth('api')->user();
        
        // Generate JWT token
        $token = auth('api')->login($user);

        return response()->json([
            'token' => $token,
            'type' => 'Bearer',
            'user' => $user
        ]);
    }
}
