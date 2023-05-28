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
    public function getArticles(Request $request): JsonResponse
    {
        //
        $articles = Article::filterBy($request->all())
            ->with('author:id,name')
            ->with('category:id,name')
            ->orderBy('id', 'DESC')
            ->whereNotNull('image_url');

        return response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $this->parseQuery($articles, $request),
        ], Response::HTTP_OK);

    }

    public function getArticle($id): JsonResponse
    {
        $article = Article::where('id', $id)->first();

        return \response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $article
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
