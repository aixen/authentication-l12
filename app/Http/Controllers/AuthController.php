<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthenticationService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'User registered successfully.',
            'user' => $result['user'],
            'token' => $result['token']
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        return response()->json([
            'message' => 'Login successful.',
            'user' => $result['user'],
            'token' => $result['token'],
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    public function profile()
    {
        $user = JWTAuth::parseToken()->authenticate();

        return response()->json([
            'message' => 'Successfully retrieved user details.',
            'user' => $user,
        ]);
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function refresh()
    {
        $newToken = JWTAuth::refresh(JWTAuth::getToken());

        return response()->json([
            'token_type' => 'bearer',
            'token' => $newToken,
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
