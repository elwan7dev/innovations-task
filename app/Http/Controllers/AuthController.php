<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Mail\UserWelcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    //use this method to signin users
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email|',
            'password' => 'required|string|min:6'
        ]);

        if (!Auth::attempt($attr)) {
            return response()->json([
                'message' => 'Credentials not match',
            ], 401);
        }

        if (auth()->user()->isBlocked){
            return response()->json([
                'message' => 'sorry, this user is blocked...! call admin for more info',
            ], 401);
        }

        $abilities = auth()->user()->roles->first()->name == 'admin' ?
            ['*'] : auth()->user()->getPermissionsViaRoles()->pluck('name')->toArray();

        // send welcome mail.
        Mail::to(auth()->user())->send(new UserWelcome(auth()->user()));

        return response()->json([
            'token' => auth()->user()->createToken('API Token', $abilities)->plainTextToken,
            'token_type' => 'Bearer',
            'user' => new UserResource(auth()->user())
        ]);
    }

    // this method signs out users by removing tokens
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }



}
