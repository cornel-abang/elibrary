<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Illuminate\Foundation\Testing\WithFaker;

class AuthServiceTest extends TestCase
{
    use WithFaker;

    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = new AuthService();
    }

    public function testRegisterUser()
    {
        $details = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password')
        ];

        $createdUser = $this->authService->registerUser($details);

        $this->assertInstanceOf(User::class, $createdUser);
    }

    public function testAttemptUserLogin()
    {
        $credentials = [
            'email' => 'testerville@example.com',
            'password' => 'password'
        ];

        $authGuard = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        $authGuard->shouldReceive('attempt')
            ->once()
            ->with($credentials)
            ->andReturn('mocked_jwt_token');

        Auth::shouldReceive('guard')
            ->once()
            ->with('api')
            ->andReturn($authGuard);

        $token = $this->authService->attemptUserLogin($credentials);

        $this->assertEquals('mocked_jwt_token', $token);
    }

    public function testFetchCurrentUser()
    {
        $user = User::factory()->create();

        $authGuard = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        $authGuard->shouldReceive('user')
            ->once()
            ->andReturn($user);

        Auth::shouldReceive('guard')
            ->once()
            ->andReturn($authGuard);

        $currentUser = $this->authService->fetchCurrentUser();

        $this->assertInstanceOf(User::class, $currentUser);
        $this->assertSame($user, $currentUser);
    }

    public function testLogUserOut()
    {
        $authGuard = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        $authGuard->shouldReceive('logout')
            ->once();

        Auth::shouldReceive('guard')
            ->once()
            ->andReturn($authGuard);

        $loggedOut = $this->authService->logUserOut();

        $this->assertTrue($loggedOut);
    }
}
