<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    //

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where('email', $request->input('email'))
            ->first();

        if ($user) {
            if ((Hash::check($request->password, $user->password))) {
                Auth::login($user);
                $token = $user->createToken('scripta')->accessToken;
                $user->token = $token;
                return response()->json(
                    [
                        'message' => 'Login successful.',
                        'user' => $user
                    ],
                    Response::HTTP_OK);
            } else {
                return response()->json(
                    [
                        'message' => 'Credentials Provided are Incorrect'
                    ],
                    Response::HTTP_UNAUTHORIZED);
            }
        } else {
            return response()->json(
                [
                    'message' => 'Credentials Provided are Incorrect'
                ],
                Response::HTTP_UNAUTHORIZED);
        }

    }

}
