<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticationController extends Controller
{
    public function __construct(private AuthService $authService){}

    public function register(RegisterUserRequest $request)
    {
        $user = $this->authService->registerUser(
            $request->validated()
        );

        $token = $this->authService->attemptUserLogin(
            $request->only('email', 'password')
        );

        return response()->json(compact('user', 'token'), 201);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authService->attemptUserLogin(
            $request->validated()
        );

        if (! $token) {
            return response()->json(
                ['error' => 'Invalid credentials'], 
                401
            );
        }

        return $this->respondWithToken($token);
    }

    public function logout()
    {
        $this->authService->logUserOut();

        return response()->json(
            ['message' => 'Successfully logged out']
        );
    }
    
    /**
     * Responsible for responses with access token 
     * 
     * @param string $token 
     * 
     * @return JsonResponse 
     */
    private function respondWithToken(string $token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}