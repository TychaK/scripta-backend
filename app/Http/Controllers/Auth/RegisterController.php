<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserCreated;
use App\Models\User;
use App\Rules\EmailMustHaveTLD;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    //
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['email', 'required', 'max:255', 'unique:users', new EmailMustHaveTLD()],
            'password' => ['required', 'string', 'min:5', 'confirmed']
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return JsonResponse
     */
    protected function register(Request $request): JsonResponse
    {
        if ($this->validator($request)->fails()) {

            return response()->json(
                [
                    'errors' => $this->validator($request)->errors(),
                    'message' => 'The data given was invalid.'
                ],
                Response::HTTP_UNPROCESSABLE_ENTITY);
        } else {

            $user = User::create([
                'name' => $request['name'],
                'email' => $request['email'],
                'phone' => $request['phone'],
                'password' => Hash::make($request['password'])
            ]);

            $uuid = Str::uuid();

            $user->update([
                'verification_hash' => $uuid
            ]);

            Mail::queue(new UserCreated($user));

            $user->verification_hash = null;

            return response()->json(
                [
                    'message' => 'Your account has been created successfully.',
                    'user' => $user,

                ], Response::HTTP_OK);

        }
    }

    public function verifyAccount(Request $request): JsonResponse
    {

        $this->validate($request, [
            'verification_hash' => ['required'],
            'email' => ['required'],
        ]);

        $entered_otp = $request->otp;

        $user = User::where('email', $request->phone ?? $request->login)
            ->first();

        if ($user->otp == $entered_otp) {

            $token = $user->createToken('scripta')->accessToken;

            $user->update([
                'active' => 1
            ]);

            return response()->json(
                [
                    'message' => 'Account activated successfully.',
                    'user' => $user,
                    'token' => $token

                ], Response::HTTP_OK);


        } else {
            return response()->json(
                [
                    "errors" => [
                        "otp" => ["The activation code provided does not match. Please try again."]
                    ],

                ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

}
