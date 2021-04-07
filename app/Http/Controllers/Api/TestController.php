<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TestRequest;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    public function lists(TestRequest $request) {
        if ($request->input('not-found')) {
            return response()->json([
                'error' => 'entity not found'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($request->input('need-authorization')) {
            return response()->json([
                'error' => 'authorization failed'
            ], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json([
            'some' => 'complex',
            'structure' => [
                'with' => 'multiple',
                'nesting' => []
            ]
        ]);
    }
}
