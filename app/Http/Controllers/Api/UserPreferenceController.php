<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        $preferences = UserPreference::filterBy($request->all());

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
    public function store(Request $request): JsonResponse
    {
        //
        $preferences = UserPreference::updateOrCreate($request->all());

        return response()->json([
            'message' => 'Data updated successfully.',
            'data' => $preferences
        ], Response::HTTP_OK);
    }

}
