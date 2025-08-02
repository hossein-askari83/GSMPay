<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Domains\User\Resources\UserResource;
use App\Domains\User\Services\UserService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
  public function __construct(private UserService $userService)
  {
  }

  /**
   * Login a user
   *
   * @param LoginRequest $request
   * @return \Illuminate\Http\JsonResponse
   * @throws JWTException
   */
  public function login(LoginRequest $request): JsonResponse
  {
    $user = $this->userService->validateCredentials(
      $request->mobile,
      $request->password
    );

    if (!$user) {
      return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }

    try {
      $token = JWTAuth::fromUser($user);
    } catch (JWTException $e) {
      return response()->json(['error' => 'Could not create token'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    return response()->json([
      'token' => $token,
      'user' => new UserResource($user),
    ]);
  }
}
