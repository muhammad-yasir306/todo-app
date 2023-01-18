<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ErrorResponse extends Response
{
    public function toResponse($message, $data = []): JsonResponse
    {
        $response = array_merge(['message' => $message], $data);
        return response()->json($response, ResponseAlias::HTTP_BAD_REQUEST);
    }
}
