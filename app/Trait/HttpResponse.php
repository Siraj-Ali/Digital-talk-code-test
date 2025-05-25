<?php

namespace App\Trait;
use Illuminate\Http\JsonResponse;
use function response;

trait HttpResponse
{
    protected function successResponse($data = null, $message = null, $code)
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }


    protected function errorResponse($message = null, $code)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $code);
    }

}
