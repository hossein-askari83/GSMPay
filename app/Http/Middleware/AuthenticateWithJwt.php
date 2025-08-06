<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateWithJwt
{
  /**
   * Authenticate a user with JWT token
   *
   * @param Request $request
   * @param Closure $next
   * @return Response
   */
  public function handle(Request $request, Closure $next): Response
  {
    try {
      JWTAuth::parseToken()->authenticate();
    } catch (Exception $e) {
      return response()->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    return $next($request);
  }
}
