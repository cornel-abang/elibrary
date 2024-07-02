<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * This class is responsible for implementing
 * all authentication actions for the entire system.
*/
class AuthService
{
    /**
     * Register user detail to database
     * 
     * @param array $details - user details
     *
     * @return User
    */
    public function registerUser(array $details)
    {
        return User::create($details);
    }

    /**
     * Attempt to log a user in with 
     * the provided credentials
     * 
     * @param array $credentials
     *
     * @return null|string
    */
    public function attemptUserLogin(array $credentials)
    {
        $token = auth('api')->attempt($credentials);

        if (! $token) {
            return null;
        }

        return $token;
    }

    /**
     * Fetch the currently authenticated user
     * 
     * @return ?User
    */
    public function fetchCurrentUser()
    {
        return auth('api')->user();
    }

    /**
     * Logout the currently authenticated user
     * 
     * @return bool
    */
    public function logUserOut()
    {
        auth('api')->logout();
        return true;
    }

    /**
     * Refresh the current users' auth token 
     * 
     * @return string - the refreshed token
    */
    public function refreshAuthToken()
    {
        return JWTAuth::parseToken()->refresh();
    }
}
