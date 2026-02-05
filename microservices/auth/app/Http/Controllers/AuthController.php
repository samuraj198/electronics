<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {}

    public function login(LoginUserRequest $request): JsonResponse
    {
        $data = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Авторизация прошла успешно',
            'data' => [
                'user' => $data['user'],
                'token' => $data['token'],
                'token_type' => $data['token_type'],
                'expires_in' => $data['expires_in'],
            ]
        ]);
    }

    public function register(RegisterUserRequest $request): JsonResponse
    {
        $data = $this->authService->register($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Регистрация прошла успешно. Вход выполнен',
            'data' => [
                'user' => $data['user'],
                'token' => $data['token'],
                'token_type' => $data['token_type'],
                'expires_in' => $data['expires_in'],
            ]
        ]);
    }

    public function logout(): JsonResponse
    {
        $check = $this->authService->logout();

        if ($check = true) {
            return response()->json([
                'success' => true,
                'message' => 'Вы успешно вышли из аккаунта'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Что-то пошло не так'
        ]);
    }
}
