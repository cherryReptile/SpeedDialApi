<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Http\Resources\UserResource;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Response;
use TheSeer\Tokenizer\Token;

class AuthController extends Controller
{
    public function register(CreateUserRequest $request): JsonResponse
    {
        $user = User::create($request->all());
        $token = $user->createToken('api')->plainTextToken;

        return Response::json([
            'user' => UserResource::make($user),
            'token' => $token
        ], 201);
    }

    public function user(Request $request)
    {

        return response()->json([
            'user' => UserResource::make($request->user())
        ]);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        //check email
        $email = $request->post('email');
        $password = $request->post('password');
        $user = User::whereEmail($email)->first();

        //check email
        if (!$user){
            return Response::json([
                'message' => 'password or email is invalid'
            ], 422);
        }
        //check password
        if (!Hash::check($password, $user->password)) {
            return Response::json([
                'message' => 'password or email is invalid'
            ], 422);
        }
        $token = $user->createToken('api')->plainTextToken;

        return Response::json([
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return Response::json([
            'message' => 'logged out'
        ]);
    }

}
