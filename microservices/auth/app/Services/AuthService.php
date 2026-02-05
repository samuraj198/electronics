<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function register(array $data): array
    {
        $user = User::create($data);

        $token = JWTAuth::fromUser($user);

        return [
            'token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ];
    }

    public function login(array $data)
    {
        if (!$token = JWTAuth::attempt($data)) {
            return false;
        }

        $user = Auth::user();

        return [
            'token' => $token,
            'user' => $user,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ];
    }

    public function logout(): bool
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        Auth::logout();

        return true;
    }
}
