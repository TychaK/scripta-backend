<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthorController extends Controller
{
    //
    public function getAuthors(Request $request): JsonResponse
    {
        $authors = Author::filterBy($request->all())
            ->select("id", 'name')
            ->get();

        return response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $authors
        ], Response::HTTP_OK);
    }
}
