<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    /**
     * @param $message
     * @param $data
     * @return JsonResponse
     */
    public function success($message = null, $data = []): JsonResponse
    {
        if (is_null($message)) {
            $message = __('Successful');
        }

        return response()->json([
            'code' => Response::HTTP_OK,
            'message' => $message,
            'data' => $data
        ], Response::HTTP_OK);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function error($message = null): JsonResponse
    {
        if (is_null($message)) {
            $message = __('Error');
        }

        return response()->json([
            'code' => Response::HTTP_FORBIDDEN,
            'message' => $message
        ], Response::HTTP_FORBIDDEN);
    }
}