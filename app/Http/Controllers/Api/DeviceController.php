<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * @OA\post(
     ** path="/api/devices/saveDeviceToken",
     *   tags={"device"},
     *   summary="save device token",
     *   operationId="save device token",
     *   security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="save device token",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *       @OA\Property(property="firebase_token", type="string",example="efsdfwewe234324234"),
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
    //save device token
    public function saveDeviceToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firebase_token' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user_id = auth()->user()->id;
        $user_device = Device::where('user_id', $user_id)->where('firebase_token', $request->firebase_token)->first();
        if ($user_device === null) {
            $user_device = Device::create([
                'user_id' => auth('api')->user()->id,
                'firebase_token' => $request->firebase_token,
            ]);
        } else {
            return response()->json(['success' => 'Token updated'], 200);
        }

        return response()->json(['success' => 'Token saved'], 200);


    }
    /**
     * @OA\post(
     ** path="/api/devices/deleteDeviceToken",
     *   tags={"device"},
     *   summary="delete device token",
     *   operationId="delete device token",
     *   security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="delete device token",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *       @OA\Property(property="firebase_token", type="string",example="efsdfwewe234324234"),
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
    //delete device token
    public function deleteDeviceToken(Request $request)
    {
        try {
            $user_firebasetoken = $request->firebase_token;
            $token_firebase = Device::where('firebase_token', $user_firebasetoken)
                ->where('user_id', auth('api')->user()->id)->first();
            if ($token_firebase) {
                $token_firebase->delete();
                return response()->json('token deleted', 200);
            } else {
                return response()->json('token does not exist', 404);
            }

        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }

    }

}
