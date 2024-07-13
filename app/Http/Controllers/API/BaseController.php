<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * success response method
     * @param mixed $result
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, string $message, int $statusCode = 200): JsonResponse
    {
        $response = [
               'success' => true,
               'data'    => $result,
               'message' => $message,
           ];
        return response()->json($response, $statusCode);
    }
    /**
     * return error response
     * @param string $error
     * @param mixed $errorMessages
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError(string $error, $errorMessages = [], int $code = 500): JsonResponse
    {
        $response = [
               'success' => false,
               'message' => $error,
           ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
