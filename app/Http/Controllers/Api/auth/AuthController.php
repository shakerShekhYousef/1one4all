<?php

namespace App\Http\Controllers\Api\auth;

use App\Http\Controllers\Controller;
use App\Models\ExerciseGroup;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     ** path="/api/auth/register",
     *   tags={"auth"},
     *   summary="Register",
     *   operationId="register",
     * @OA\RequestBody(
     *    required=true,
     *    description="Register",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *      required={"name","email","password","password_confirmation","role_id"},
     *       @OA\Property(property="name", type="string", example="user"),
     *       @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *       @OA\Property(property="password", type="string", format="password", example="secretuser"),
     *       @OA\Property(property="password_confirmation", type="string", format="password", example="secretuser"),
     *       @OA\Property(property="mobile", type="string", example="+9712323232"),
     *       @OA\Property(property="role_id", type="number",description="2 for specialist / 3 for member"),
     *       @OA\Property(property="country_code", type="string",description="Country code"),
     *       @OA\Property(property="level_id", type="number",description="1 beginner/2 advanced/3 athlete"),
     *       @OA\Property(property="subcategory_id", type="number"),
     *      )
     *    )
     *  ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     *
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role_id' => 'required',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = $request->role_id;
            $user->mobile = $request->mobile;
            $user->level_id = $request->level_id;
            $user->subcategory_id = $request->subcategory_id;
            $user->approved =1;
            $user->country_code = $request->country_code;
            $user->save();
            $user_id = $user->id;

            DB::transaction(function () use ($user_id) {
                $plan = Plan::create(['user_id' => $user_id]);
                ExerciseGroup::create(['plan_id' => $plan["id"], 'date' => Carbon::now()->format('Y-m-d')]);
            });

            $token = JWTAuth::getFacadeRoot()->fromUser($user);
            return response()->json(['status' => true, 'token' => $token,
                'user' => UserResource::make($user),], 201);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json(['status' => false, 'Messages' => $ex->getMessage()], 500);
        }


    }

    /**
     * @OA\Post(
     ** path="/api/auth/login",
     *   tags={"auth"},
     *   summary="Login",
     *   operationId="login",
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass user credentials",
     *    @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="admin@example.com"),
     *       @OA\Property(property="password", type="string", format="password", example="secretadmin"),
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     **/
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        try {
            $credentials = request(['email', 'password']);
            if ($token = JWTAuth::attempt($credentials, ['exp' => Carbon::now()->addYear(7)->timestamp])) {
                $user = $request->user();
                if ($user->is_active === 0) {
                    return response()->json(['error' => 'Your account is deactivated'], 401);
                }
                return response()->json([
                    'access_token' => $this->respondWithToken($token),
                    'token_type' => 'Bearer',
                    'user' => UserResource::make($user)
                ], 200);
            } else {
                return response()->json(['status' => false, 'Messages' => 'Invalid Data '], 401);
            }
        } catch (JWTException $ex) {
            return response()->json(['status' => false, 'Messages' => $ex->getMessage()], 500);
        }

    }

    /**
     * @OA\Get(
     * path="/api/auth/logout",
     * summary="Logout",
     * description="Logout user and invalidate token",
     * operationId="authLogout",
     * tags={"auth"},
     * security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     */
    public function logout(Request $request)
    {
        auth('api')->logout();
        return response()->json(['message' => 'User successfully logged out'], 200);
    }

    /**
     * @OA\Get(
     * path="/api/auth/user",
     * summary="get user information",
     * description="get user information",
     * operationId="authUser",
     * tags={"auth"},
     * security={{ "apiAuth": {} }},
     *   @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=201,
     *       description="Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     *   @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     *   @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     */
    public function user(Request $request)
    {
        return response()->json(UserResource::make($request->user()));
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
        ]);
    }

}
