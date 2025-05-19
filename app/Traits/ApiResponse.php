<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Validator;

trait ApiResponse
{
    //
    public function sendResponse($data, string $message = '', $code = 200): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    public function sendError($error, $errorMessages = [], $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
            'data' => $errorMessages,
        ];

        return response()->json($response, $code);
    }

    public function validationError(Validator $validator): JsonResponse
    {
        return $this->sendError('Validation Error.', $validator->errors());
    }
}
