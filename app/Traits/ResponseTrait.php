<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Resources\Json\JsonResource;


trait ResponseTrait
{

    public function SuccessResponse($data = null, $message = null, $statusCode = 200)
    {
        return response()->json(['status' => true, 'message' => $message, 'data' => $data, 'statusCode' => $statusCode], $statusCode);
    }

    public function SuccessPaginationResponse(JsonResource $data = null, $message = null, $statusCode = 200)
    {
        $data = $data->response()->getData(true);
        if (is_array($data)) {
            if (array_key_exists('links', $data)) {
                unset($data['links']);
            }
            if (array_key_exists('meta', $data) && array_key_exists('links', $data['meta'])) {
                unset($data['meta']['links']);
            }
        }

        return response()->json(['status' => true, 'type' => 'pagination', 'message' => $message, 'data' => $data, 'statusCode' => $statusCode], $statusCode);
    }

    public function ErrorResponse($message = null, $errors = null, $errorCode = 400)
    {
        return response()->json(['status' => false, 'message' => $message, 'errors' => $errors, 'statusCode' => $errorCode], $errorCode);
    }

    public function WarningResponse($data = null, $message = null, $statusCode = 200)
    {
        return response()->json(['status' => true, 'message' => $message, 'data' => $data, 'statusCode' => $statusCode]);
    }

    public function ValidationResponse($key = null, $message = null, $statusCode = 422)
    {
        throw ValidationException::withMessages([$key => $message]);
    }
}
