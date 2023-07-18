<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use App\Models\ExerciseGroup;
use App\Models\Notification;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Request as Trequest;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Get(
     * path="/api/request/getOwnRequest",
     * summary="get own request",
     * description="if the user is trainer => get the requests sent to trainer
     * if the user is a player  => get requests that this user sent",
     * tags={"request"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="type",
     * in="query",
     * required=false,
     * description="type",
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
    //get own requests
    public function getOwnRequest()
    {
        $type = isset($_GET['type']) ? $_GET['type'] : null;
        try {
            $auth_user = auth('api')->user();
            if ($auth_user->role_id == 2) {
                $requests = Trequest::where('trainer_id', $auth_user->id)
                    ->type($type)
                    ->orderBy('created_at', 'desc')->paginate(10);
            } elseif ($auth_user->role_id == 3) {
                $requests = Trequest::where('player_id', $auth_user->id)
                    ->type($type)
                    ->orderBy('created_at', 'desc')->paginate(10);
            }
            return response()->json(RequestResource::collection($requests)->response()->getData(true));

        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
    /**
     * @OA\post(
     ** path="/api/request/send",
     *   tags={"request"},
     *   summary="send new request",
     *   operationId="send new request",
     *   security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="send new request",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *       required={"body","trainer_id"},
     *       @OA\Property(property="body", type="string", example="lorem ipsum"),
     *       @OA\Property(property="trainer_id", type="number"),
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
    //send new request
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trainer_id' => 'required',
            'body' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        //get trainer id
        $trianer_id = $request->trainer_id;
        //get player id
        $player_id = auth('api')->user()->id;
        //store request
        try {
            $Trequest = Trequest::create([
                'body' => $request->body,
                'player_id' => $player_id,
                'trainer_id' => $trianer_id,
                'request_type' => \App\Models\Request::DEFAULT,
            ]);
            //send notification to trainer
            $receiver_id = $Trequest->trainer_id;
            //from player
            $player_name = $Trequest->player->name;
            $message = $player_name . ' has sent you a request.';
            $notification = Notification::create([
                'title' => 'New Request',
                'text' => $message,
                'user_id' => $receiver_id,
                'sender_id' => $Trequest->player_id,
                'notification_type' => 'New Request',
                'request_id' => $Trequest->id
            ]);
            event(new NotificationCreatedEvent($notification, $receiver_id));
            return response()->json(['message' => 'Request sent', 'status' => '200 success'], 200);

        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Get(
     * path="/api/request/show/{request_id}",
     * summary="show request",
     * description="show request",
     * tags={"request"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="request_id",
     * in="path",
     * required=true,
     * description="request_id",
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
    public function show($id)
    {
        try {
            $request = Trequest::findOrFail($id);
            return response()->json(RequestResource::make($request));
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Put(
     * path="/api/request/changeType/{request_id}",
     * summary="edit request",
     * description="edit request",
     * tags={"request"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="request_id",
     * in="path",
     * required=true,
     * description="request_id",
     * ),
     * @OA\Parameter(
     * name="request_type",
     * in="query",
     * required=true,
     * description="request_type",
     * ),
     * @OA\Parameter(
     * name="trainer_id",
     * in="query",
     * required=true,
     * description="trainer_id",
     * ),
     * @OA\Response(
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
    public function changeType($id)
    {

        $request_type = $_GET['request_type'];
        $auth_user = auth('api')->user();
        $trainer_id = $_GET['trainer_id'];
        try {
            //Find Request
            $Trequest = Trequest::findOrFail($id);
            //attach trainer to and create plan
            if ($request_type == "Paid") {
                //Edit status
                if($Trequest->request_type==="Paid"){
                    return response()->json(['message'=>'You already paid'],400);
                }
                $Trequest->update(['request_type' => $request_type]);
                $plan = Plan::create([
                    'user_id' => auth('api')->user()->id,
                    'trainer_id' => $trainer_id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $group = ExerciseGroup::create(['plan_id' => $plan->id, 'date' => Carbon::now()->format('Y-m-d')]);
            }
            //send notification
            if ($request_type == 'Completed') {
                $Trequest->update(['request_type' => $request_type]);
                $message = $Trequest->trainer->name . ' has marked your request as completed.';
                $type = 'Request Completed';
                $sender = $Trequest->trainer_id;
                self::sendNotification($Trequest->player_id, $sender, $message, $type, $id);
            } elseif ($request_type == 'Paid') {
                $message = $Trequest->player->name . ' has completed the payment process, click to add a plan.';
                $type = 'Payment Completed';
                $sender = $Trequest->player_id;
                self::sendNotification($Trequest->trainer_id, $sender, $message, $type, $id);
            } elseif ($request_type == 'Accepted') {
                $Trequest->update(['request_type' => $request_type]);
                $message = $Trequest->trainer->name . ' has accepted your request.';
                $type = 'Request Accepted';
                $sender = $Trequest->trainer_id;
                self::sendNotification($Trequest->player_id, $sender, $message, $type, $id);
            } elseif ($request_type == 'Closed') {
                $Trequest->update(['request_type' => $request_type]);
                $message = $Trequest->trainer->name . ' has closed your request.';
                $type = 'Request Closed';
                $sender = $Trequest->trainer_id;
                self::sendNotification($Trequest->player_id, $sender, $message, $type, $id);
            } elseif ($request_type == 'Rejected') {
                $Trequest->update(['request_type' => $request_type]);
                $message = $Trequest->trainer->name . ' has rejected your request.';
                $type = 'Request Rejected';
                $sender = $Trequest->trainer_id;
                self::sendNotification($Trequest->player_id, $sender, $message, $type, $id);
            }
            //response
            return response()->json(['message' => 'Request updated', 'status' => '200 success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    //send notification
    public static function sendNotification($receiver_id, $sender, $message, $type, $id)
    {
        $notification = Notification::create([
            'title' => $type,
            'text' => $message,
            'user_id' => $receiver_id,
            'sender_id' => $sender,
            'notification_type' => $type,
            'request_id' => $id
        ]);
        event(new NotificationCreatedEvent($notification, $receiver_id));
    }

    public function destroy($id)
    {
        //
    }
}
