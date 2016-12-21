<?php

namespace App\Http;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class Controller
{
    protected function success($data) : JsonResponse
    {
        return $this->response(
            'success',
            $data,
            200
        );
    }

    protected function notFound(string $message) : JsonResponse
    {
        return $this->response(
            'error',
            [$message],
            404
        );
    }

    protected function badRequest($messages) : JsonResponse
    {
        return $this->response(
            'error',
            $messages,
            400
        );
    }

    protected function response($code, $data, $status) : JsonResponse
    {
        return response()
            ->json(
                (object) [
                    'code' => $code,
                    'data' => $data
                ],
                $status
            );
    }
}