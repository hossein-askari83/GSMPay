<?php

use App\Domains\User\Models\User;
use App\Domains\User\Services\UserService;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);


it('logs in successfully and returns token with user', function () {
  $user = User::factory()->create();

  $mockService = Mockery::mock(UserService::class);
  $mockService->shouldReceive('validateCredentials')
    ->once()
    ->with($user->mobile, UserFactory::$password)
    ->andReturn($user);

  $this->instance(UserService::class, $mockService);

  JWTAuth::shouldReceive('fromUser')
    ->once()
    ->with($user)
    ->andReturn('fake_jwt_token');

  $response = $this->postJson(route('auth.login'), [
    'mobile' => $user->mobile,
    'password' => UserFactory::$password,
  ]);

  $response->assertStatus(200)
    ->assertJsonStructure(['data' => ['token', 'user']])
    ->assertJsonFragment(['token' => 'fake_jwt_token']);
});

it('returns 401 for invalid credentials', function () {
  $user = User::factory()->create();

  $mockService = Mockery::mock(UserService::class);
  $mockService->shouldReceive('validateCredentials')
    ->once()
    ->with($user->mobile, 'wrongpass')
    ->andReturn(null);
  $this->instance(UserService::class, $mockService);

  $response = $this->postJson(route('auth.login'), [
    'mobile' => $user->mobile,
    'password' => 'wrongpass',
  ]);

  $response->assertStatus(401)
    ->assertJsonFragment(['message' => 'Invalid credentials']);
});

it('returns 500 if JWT token creation fails', function () {
  $user = User::factory()->create();

  $mockService = Mockery::mock(UserService::class);
  $mockService->shouldReceive('validateCredentials')
    ->once()
    ->with($user->mobile, $user->password)
    ->andReturn($user);
  $this->instance(UserService::class, $mockService);

  JWTAuth::shouldReceive('fromUser')
    ->once()
    ->andThrow(new JWTException());

  $response = $this->postJson(route('auth.login'), [
    'mobile' => $user->mobile,
    'password' => $user->password,
  ]);

  $response->assertStatus(500)
    ->assertJsonFragment(['error' => 'Could not create token']);
});

it('refreshes token successfully', function () {
  $this->withoutMiddleware();

  $caller = User::factory()->create();
  $token = JWTAuth::fromUser($caller);


  JWTAuth::shouldReceive('parseToken->refresh')
    ->once()
    ->andReturn('new_fake_jwt_token');

  $response = $this->withHeader('Authorization', "Bearer {$token}")
    ->postJson(route('auth.refresh'));

  $response->assertStatus(200)
    ->assertJsonFragment(['token' => 'new_fake_jwt_token']);
});

it('returns 401 if refresh fails', function () {
  $this->withoutMiddleware();

  $caller = User::factory()->create();
  $token = JWTAuth::fromUser($caller);

  JWTAuth::shouldReceive('parseToken->refresh')
    ->once()
    ->andThrow(new JWTException());

  $response = $this->withHeader('Authorization', "Bearer {$token}")
    ->postJson(route('auth.refresh'));

  $response->assertStatus(401)
    ->assertJsonFragment(['error' => 'Unauthorized']);
});