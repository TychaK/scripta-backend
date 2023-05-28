<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategories(Request $request): JsonResponse
    {
        //
        $categories = Category::filterBy($request->all())
            ->get();

        return response()->json([
            'message' => 'Data fetched successfully',
            'data' => $categories,
        ], Response::HTTP_OK);
    }
}
