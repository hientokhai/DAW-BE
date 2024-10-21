<?php

namespace App\Traits;

trait JsonResponse
{
    protected function successResponse($data, $message = '', $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    protected function errorResponse($message = '', $code = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'data' => null
        ], $code);
    }

    protected function notFoundResponse($message = '', $code = 404)
    {
        return response()->json([
            'status' => 'not-found',
            'message' => $message,
            'data' => null
        ], $code);
    }
}