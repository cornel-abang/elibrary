<?php

namespace Tests\Unit\Controllers;

use Mockery;
use Tests\TestCase;
use App\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\AuthenticationController;

class AuthenticationControllerTest extends TestCase
{
    protected $authService;
    protected $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = Mockery::mock(AuthService::class);
        $this->controller = new AuthenticationController($this->authService);
    }

    public function testRegister()
    {
        $userData = [
            'name' => 'John Doe', 
            'email' => 'atest@mail.com', 
            'password' => 'password'
        ];

        $request = Mockery::mock(RegisterUserRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($userData);

        $loginData = [
            'email' => 'atest@mail.com', 
            'password' => 'password'
        ];

        $request->shouldReceive('only')
            ->once()
            ->andReturn($loginData);

        $this->authService->shouldReceive('registerUser')
            ->once()
            ->with($userData)
            ->andReturn(['name' => 'John Doe', 'email' => 'atest@mail.com']);

        $token = 'mocked_jwt_token';
        $this->authService->shouldReceive('attemptUserLogin')
            ->once()
            ->with($loginData)
            ->andReturn($token);

        $response = $this->controller->register($request);

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }

    public function testLoginWithValidCredentials()
    {
        $loginData = [
            'email' => 'atest@mail.com', 
            'password' => 'password'
        ];

        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($loginData);

        $token = 'mocked_jwt_token';
        $this->authService->shouldReceive('attemptUserLogin')
            ->once()
            ->with($loginData)
            ->andReturn($token);

        JWTAuth::shouldReceive('factory->getTTL')->andReturn(60);

        $response = $this->controller->login($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertArrayHasKey('access_token', json_decode($response->getContent(), true));
        $this->assertArrayHasKey('token_type', json_decode($response->getContent(), true));
        $this->assertArrayHasKey('expires_in', json_decode($response->getContent(), true));
    }

    public function testLoginWithInvalidCredentials()
    {
        $loginData = [
            'email' => 'atest@mail.com', 
            'password' => 'password'
        ];

        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($loginData);

        $this->authService->shouldReceive('attemptUserLogin')
            ->once()
            ->with($loginData)
            ->andReturn(null); // invalid credntials

        $response = $this->controller->login($request);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertArrayHasKey('error', json_decode($response->getContent(), true));
    }

    public function testLogout()
    {
        $this->authService->shouldReceive('logUserOut')
            ->once();

        $response = $this->controller->logout();

        $expMessage = 'Successfully logged out';
        $respArray = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJson($response->getContent());
        $this->assertArrayHasKey('message', $respArray);
        $this->assertEquals(
            $respArray['message'],
            $expMessage
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}