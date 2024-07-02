<?php

namespace Tests\Feature\Controllers;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testRegister()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'doe@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'John Doe', 'email' => 'doe@example.com']);
    }

    public function testLoginWithValidCredentials()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password')
        ]);

        $credentials = [
            'email' => $user->email,
            'password' => 'password'
        ];

        $response = $this->postJson('/api/auth/login', $credentials);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'expires_in'
                 ]);
    }

    public function testLoginWithInvalidCredentials()
    {
        $credentials = [
            'email' => 'doesntexist@example.com',
            'password' => 'invalid_password'
        ];

        $response = $this->postJson('/api/auth/login', $credentials);

        $response->assertStatus(401)
                 ->assertJson([
                     'error' => 'Invalid credentials'
                 ]);
    }

    public function testMe()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->getJson('/api/auth/me');

        $response->assertStatus(200)
                 ->assertJson([
                     'id' => $user->id,
                     'email' => $user->email
                 ]);
    }

    public function testLogout()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/auth/logout');

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Successfully logged out'
                 ]);
    }

    public function testRefresh()
    {
        $user = User::factory()->create();

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}",
        ])->postJson('/api/auth/refresh');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'access_token',
                     'token_type',
                     'expires_in'
                 ]);
    }
}