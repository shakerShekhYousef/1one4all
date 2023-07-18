<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JwtController extends Controller
{
    public function __construct()
    {
        Auth::shouldUse('api');
    }

    public function getAuthenticatedUser()
    {
        try {
            if (! $user = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], 401);

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], 401);

        } catch (JWTException $e) {

            return response()->json(['token_absent'], 401);

        }

        return $user;
    }
}
