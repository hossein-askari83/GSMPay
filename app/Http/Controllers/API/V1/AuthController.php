<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
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
   * @throws JWTException
   * @return JsonResponse
   */
  public function login(LoginRequest $request): JsonResponse
  {
    $user = $this->userService->validateCredentials(
      $request->mobile,
      $request->password
    );

    if (!$user) {
      return $this->response(['message' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
    }

    try {
      $token = JWTAuth::fromUser($user);
    } catch (JWTException $e) {
      return $this->response(['error' => 'Could not create token'], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    return $this->response([
      'token' => $token,
      'user' => new UserResource($user),
    ]);
  }

  public function refresh(): JsonResponse
  {
    try {
      $newToken = JWTAuth::parseToken()->refresh();
    } catch (JWTException $e) {
      return $this->response(
        ['error' => 'Unauthorized'],
        Response::HTTP_UNAUTHORIZED
      );
    }

    return $this->response([
      'token' => $newToken,
    ]);
  }
}
