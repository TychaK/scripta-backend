<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSources(Request $request): JsonResponse
    {
        //
        $sources = Client::filterBy($request->all())
            ->select('id', 'name');

        return response()->json([
            'message' => 'Data fetched successfully.',
            'data' => $this->parseQuery($sources, $request)
        ], Response::HTTP_OK);
    }
}
