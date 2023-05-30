<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserAuthorPreference;
use App\Models\UserCategoryPreference;
use App\Models\UserSourcePreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        //
        $preferences = UserCategoryPreference::filterBy($request->all());

        return response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $preferences
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function savePreferences(Request $request): JsonResponse
    {
        //

        $authors = $request->authors ?? [];

        $categories = $request->categories ?? [];

        $sources = $request->sources ?? [];

        collect($authors)->each(function ($author) use ($request) {
            UserAuthorPreference::updateOrCreate([
                'user_id' => Auth::id(),
                'author_id' => $author['value'] ?? $author['id']
            ]);
        });

        collect($categories)->each(function ($category) use ($request) {
            UserCategoryPreference::updateOrCreate([
                'user_id' => Auth::id(),
                'category_id' => $category['value'] ?? $category['id']
            ]);
        });

        collect($sources)->each(function ($source) use ($request) {
            UserSourcePreference::updateOrCreate([
                'user_id' => Auth::id(),
                'source_id' => $source['value'] ?? $source['id']
            ]);
        });

        return response()->json([
            'message' => 'Your preferences have been updated successfully.',
            'data' => [
                'categories' => $categories,
                'authors' => $authors
            ]
        ], Response::HTTP_OK);
    }

}
