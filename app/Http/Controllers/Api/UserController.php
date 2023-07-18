<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\UserResource;
use App\Models\Certificate;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index()
    {
        //
    }
    /**
     * @OA\Get(
     * path="/api/user/trainers",
     * summary="get all trainers",
     * description="get all trainers",
     * tags={"user"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="user_name",
     * in="query",
     * required=false,
     * description="search by name",
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * required=false,
     * description="search by page",
     * ),
     * @OA\Parameter(
     * name="category",
     * in="query",
     * required=false,
     * description="search by category",
     * ),
     * @OA\Parameter(
     * name="subcategory",
     * in="query",
     * required=false,
     * description="search by subcategory",
     * ),
     * @OA\Parameter(
     * name="level",
     * in="query",
     * required=false,
     * description="search by level id",
     * ),
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
    //return all trainers
    public function getTrainers()
    {
        $name = isset($_GET['user_name']) ? $_GET['user_name'] : null;
        $subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : null;
        $category = isset($_GET['category']) ? $_GET['category'] : null;
        $level = isset($_GET['level']) ? $_GET['level'] : null;
        if ($category == null) {
            $query=User::where([['role_id', 2], ['approved', 1]])
                ->name($name)
                ->subcategory($subcategory)
                ->whereNotIn('id',[auth('api')->user()->id]);
            if($level==null){
                $trainers = clone $query->paginate(10);
            }else{
                $trainers = clone $query->level($level)->paginate(10);
            }

        } else if ($level == null) {
            $query=User::where([['role_id', 2], ['approved', 1]])
                ->name($name)
                ->subcategory($subcategory)
                ->whereNotIn('id',[auth('api')->user()->id]);
            if($category==null){
                $trainers = clone $query->paginate(10);
            }else{
                $trainers = clone $query->category($category)->paginate(10);
            }
        }
        return response()->json(UserResource::collection($trainers)->response()->getData(true));
    }
    /**
     * @OA\Get(
     * path="/api/user/players",
     * summary="get all players",
     * description="get all players",
     * tags={"user"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="user_name",
     * in="query",
     * required=false,
     * description="search by name",
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * required=false,
     * description="search by page",
     * ),
     * @OA\Parameter(
     * name="level",
     * in="query",
     * required=false,
     * description="search by level id",
     * ),
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
    //return all Players
    public function getPlayers()
    {
        //search parameters
        $name = isset($_GET['user_name']) ? $_GET['user_name'] : null;
        $level = isset($_GET['level']) ? $_GET['level'] : null;
        //response
        $players = User::where('role_id',3)->name($name)->level($level)->whereNotIn('id',[auth('api')->user()->id])->paginate(10);
        return response()->json(UserResource::collection($players)->response()->getData(true));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }
    /**
     * @OA\post(
     ** path="/api/user/update",
     *   tags={"user"},
     *   summary="Update user info",
     *   operationId="update user info",
     *   security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="update user info",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *       @OA\Property(property="name", type="string", example="user"),
     *       @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *       @OA\Property(property="mobile", type="string", example="+9712323232"),
     *       @OA\Property(property="age", type="string", example="23"),
     *       @OA\Property(property="subcategory_id", type="number"),
     *       @OA\Property(property="bio", type="string",example="lorem ipsom"),
     *       @OA\Property(property="level_id", type="number",description="1 beginner/2 advanced/3 athlete"),
     *       @OA\Property(property="profile_pic", type="file"),
     *       @OA\Property(property="country_code", type="string",description="Country code"),
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
    //update user info
    public function update(Request $request)
    {
        //find user
        $user = auth('api')->user();
        //validate email
        if ($request->email !== $user->email) {
            $validator = Validator::make($request->all(), [
                'email' => 'string|email|unique:users',
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
            $user->email = $request->email;
            $user->save();
        }else{

        }
        //update profile image
        if ($request->hasFile('profile_pic')) {
            $validator = Validator::make($request->all(), [
                'profile_pic' => 'mimes:jpeg,png,jpg,gif,svg,pdf|max:2048',
            ]);
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }
        }
        try {
            //store image
            if ($request->hasFile('profile_pic')) {
                $fileNameTostore = $this->UploadFile($request->profile_pic, 'users');
            } else {
                $fileNameTostore = $user->profile_pic;
            }
            //update user
            $user->update([
                'name' => $request->name,
                'age' => $request->age,
                'profile_pic' => $fileNameTostore,
                'mobile' => $request->mobile,
                'bio' => $request->bio,
                'subcategory_id' => $request->subcategory_id,
                'country_code' => $request->country_code,
                'level_id' => $request->level_id
            ]);
            if ($user)
                return response()->json(['message' => 'User Updated', 'user' => UserResource::make($user), 'status' => '200 success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }

    }
    /**
     * @OA\post(
     ** path="/api/user/updatePassword",
     *   tags={"user"},
     *   summary="update password",
     *   operationId="update password",
     *   security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="update password",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *       required={"password","password_confirmation","old_password"},
     *       @OA\Property(property="old_password", type="string",format="password"),
     *       @OA\Property(property="password", type="string",format="password"),
     *       @OA\Property(property="password_confirmation",  type="string",format="password"),
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
    //update password
    public function updatePassword(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|string|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        try {
            $auth_user = auth('api')->user();
            if (!Hash::check($request->old_password, $auth_user->password)) {
                return response()->json(['errors' => 'Old password not correct'], 400);
            }
            $auth_user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json(['message' => 'Password Updated', 'status' => '200 success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * @OA\Get(
     * path="/api/user/myTrainers",
     * summary="get my trainers",
     * description="get my trainers",
     * tags={"user"},
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
    //return my trainers
    public function myTrainers()
    {
        try {
            $trainers = User::whereIn('id', function ($query) {
                $query->from('plans')->where('user_id', auth('api')->user()->id)
                    ->select('trainer_id')
                    ->get();
            })->where([['role_id', 2], ['approved', 1]])->get();
            return response()->json(UserResource::collection($trainers)->response()->getData(true));

        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }

    }
    /**
     * @OA\Get(
     * path="/api/user/myPlayers",
     * summary="get my players",
     * description="get my players",
     * tags={"user"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="user_name",
     * in="query",
     * required=false,
     * description="search by name",
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * required=false,
     * description="search by page",
     * ),
     * @OA\Parameter(
     * name="level",
     * in="query",
     * required=false,
     * description="search by level id",
     * ),
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
    //return my players
    public function myPlayers()
    {
        //search parameters
        $name = isset($_GET['user_name']) ? $_GET['user_name'] : null;
        $level = isset($_GET['level']) ? $_GET['level'] : null;
        //query
        $query_players = User::whereIn('id', function ($query) {
            $query->from('plans')->where('trainer_id', auth('api')->user()->id)
                ->select('user_id')
                ->get();
        })->name($name);
        //response
        if ($level==null){
            $players=clone $query_players->paginate(10);
        }else{
            $players=clone $query_players->level($level)->paginate(10);
        }

        return response()->json(UserResource::collection($players)->response()->getData(true));
    }
    /**
     * @OA\Get(
     * path="/api/notifications",
     * summary="get all notifications",
     * description="get all notifications",
     * tags={"user"},
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
    //get all notifications by auth user id
    public function getNotifications(){
        try {
            $notifications=Notification::where([
                ['user_id',auth()->user()->id],
                ['notification_type','<>','message'],
            ])->orderBy('created_at','desc')->paginate(10);
            return response()->json(NotificationResource::collection($notifications)->response()->getData(true),200);
        }catch (\Throwable $th){
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
    /**
     * @OA\Post(
     * path="/api/user/resetProfileImage",
     * summary="Reset Profile Image",
     * description="Reset Profile Image",
     * tags={"user"},
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
    //Reset user profile image
    public function resetProfileImage(){
        try {
            //find user
            $user=auth('api')->user();
            //remove image
            if ($user->profile_pic !=='user.png'){
                unlink(storage_path('app/public/users/'.$user->profile_pic));
            }
            //reset image to default
            $user->update([
                'profile_pic'=>'user.png',
            ]);
            return response()->json(['message'=>'Your profile picture has been reset to default']);

        }catch (\Throwable $th){
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
    // Upload image function
    public function UploadFile($file, $path)
    {
        //get file name with extention
        $filenameWithExt = $file->getClientOriginalName();
        //get just file name
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        //GET EXTENTION
        $extention = $file->getClientOriginalExtension();
        //file name to store
        $fileNameToStore = $filename . '_' . time() . '.' . $extention;
        //upload image
        $path = $file->storeAs('public/' . $path . '/', $fileNameToStore);
        return $fileNameToStore;
    }
}
