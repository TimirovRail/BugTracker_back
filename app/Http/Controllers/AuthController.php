<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:admin,developer,tester,manager',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Неверные учетные данные'], 401);
        }

        $user = Auth::user();
        $tokenResult = $user->createToken('authToken');
        $accessToken = $tokenResult->plainTextToken;

        $refreshToken = Str::random(60);
        $user->update(['refresh_token' => hash('sha256', $refreshToken)]);

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
            'refresh_token' => $refreshToken
        ]);
    }
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        Password::sendResetLink($request->only('email'));

        return response()->json(['message' => 'Password reset link sent']);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );

        return $status == Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['message' => 'Invalid token'], 400);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
    public function refreshToken(Request $request)
    {
        $refreshToken = $request->input('refresh_token');

        $user = User::where('refresh_token', hash('sha256', $refreshToken))->first();

        if (!$user) {
            return response()->json(['message' => 'Неверный refresh-токен'], 401);
        }

        $newAccessToken = $user->createToken('authToken')->plainTextToken;
        $newRefreshToken = Str::random(60);
        $user->update(['refresh_token' => hash('sha256', $newRefreshToken)]);

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken
        ]);
    }

}
