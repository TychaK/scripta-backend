<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        //
        $articles = Article::filterBy($request->all())
            ->with('author:id,name')
            ->with('category:id,name')
            ->paginate($request->input('per_page', 25));;

        return response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $articles,
        ], Response::HTTP_OK);

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): JsonResponse
    {
        //
        $article = Article::findOrFail($id);

        return \response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $article,
        ], Response::HTTP_OK);
    }

}
