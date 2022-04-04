<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{

    /**
     * Return generic json response with the given data.
     *
     * @param $data
     * @param int $statusCode
     * @param array $headers
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respond($data, $statusCode = 200, $message = '', $headers = [])
    {
        $newData = [];

        $newData['data'] = $data;

        $newData['success'] = [
            'message' => $message
        ];

        return response()->json($newData, $statusCode, $headers);
    }
    
    /**
     * Respond with error.
     *
     * @param string $message
     * @param int $statusCode
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondError($message, $statusCode)
    {
        return response()->json(['errors' => $message], $statusCode);
    }
    
    /**
     * Respond with success
     *
     * @param array $data
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondSuccess($data, $message = '')
    {
        return $this->respond($data, 200, $message);
    }

    /**
     * Respond with created.
     *
     * @param array $data
     * @param string $message
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondCreated($data, $message = '')
    {
        return $this->respond($data, 201, $message);
    }

    /**
     * Respond with not found.
     *
     * @param string $message
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondNotFound($message = 'Not Found')
    {
        return $this->respondError($message, 404);
    }
}