<?php

namespace App\Http\Controllers\Api;

use App\Http\Controller\Controller;
use App\Http\Request\LoginRequest;
use App\Model\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     *  POST \api\login - validate credentials, issue a sanctum bearer token ( the "session)
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Invalide credentials.'], 401);
        }

        /** @var User User */
        $user = Auth::user();

        $token = $user->createToken(
            name: 'sesssion-'.Str::random(8),
        )->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer'
        ]);
    }

    /**
     * POST /api/logout - revoque the token used for the current request
     */
    public function logout(Request $request):Jsonresponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Loggged out.']);
    }

    /**
     * GET /api/me - the Authenticated user.
     */

    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
