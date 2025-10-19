<?php

namespace App\Trait;

trait ApiResponse
{


    protected function successResponse($data, $message = 'Success', $code = 200, $title = 'Success')
    {
        return response()->json([
            'status' => 'success',
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse($message, $code = 400, $title = 'Error')
    {
        return response()->json([
            'status' => 'failed',
            'title' => $title,
            'message' => $message,
        ], $code);
    }

    protected function warningResponse($message, $data = null, $code = 200, $title = 'Warning')
    {
        return response()->json([
            'status' => 'warning',
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function notFoundResponse($message = 'Resource not found', $title = 'Not Found')
    {
        return $this->errorResponse($message, 404, $title);
    }
    protected function validationErrorResponse($errors, $message = 'Validation Error', $title = 'Validation Error')
    {
        return response()->json([
            'status' => 'failed',
            'title' => $title,
            'message' => $message,
            'errors' => $errors,
        ], 422);
    }


    protected function unauthorizedResponse($message = 'Unauthorized', $title = 'Unauthorized')
    {
        return $this->errorResponse($message, 401, $title);
    }

    protected function forbiddenResponse($message = 'Forbidden', $title = 'Forbidden')
    {
        return $this->errorResponse($message, 403, $title);
    }




     // GraphQL-safe helpers (plain arrays, always include message + type)

    protected function gqlSuccess(string $title, string $message, ?array $data = null): array
    {
        return ['success' => true, 'type' => 'success', 'title' => $title, 'message' => $message, 'data' => $data];
    }

    protected function gqlInfo(string $title, string $message, ?array $data = null): array
    {
        return ['success' => true, 'type' => 'info', 'title' => $title, 'message' => $message, 'data' => $data];
    }

    protected function gqlWarning(string $title, string $message, ?array $data = null): array
    {
        return ['success' => false, 'type' => 'warning', 'title' => $title, 'message' => $message, 'data' => $data];
    }

    protected function gqlNotFound(string $title, string $message, ?array $data = null): array
    {
        return ['success' => false, 'type' => 'not_found', 'title' => $title, 'message' => $message, 'data' => $data];
    }

    protected function gqlError(string $title, string $message, ?array $data = null): array
    {
        return ['success' => false, 'type' => 'error', 'title' => $title, 'message' => $message, 'data' => $data];
    }









}
