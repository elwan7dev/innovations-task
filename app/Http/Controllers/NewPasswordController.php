<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NewPasswordController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ( $status === Password::RESET_LINK_SENT){
            return response()->json(['status' => __($status)]);
        }

        throw ValidationException::withMessages([
            'email' => __($status)
        ]);

    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
                $user->tokens()->delete();
//                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET){
            return response()->json(['status' => __($status)]);
        }
        return response()->json([
            'status' => __($status),
        ], 500);
    }
}
