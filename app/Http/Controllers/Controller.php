<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    /**
     * Wrap final response 
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    protected function response(mixed $data, int $status = 200): JsonResponse
    {
        return response()->json(['data' => $data, 'server_time' => now()], $status);
    }
}
