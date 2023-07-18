<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Exercise;
use App\Models\ExerciseGroup;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/plans",
     * summary="get plans / get plans for a specific month.",
     * description="get plans / get plans for a specific month.",
     * tags={"plan"},
     *   security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="date",
     * in="query",
     * required=false,
     * description="search by month(01,02,03,04....ex) example:2021-10",
     * ),
     * @OA\Parameter(
     * name="page",
     * in="query",
     * required=false,
     * description="search by page",
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
    public function index()
    {
        try {
            $date = isset($_GET['date']) ? $_GET['date'] : null;
            if ($date !== null)
                $plans = Plan::where('user_id', auth('api')->user()->id)->date($date)->get();
            else
                $plans = Plan::where('user_id', auth('api')->user()->id)->get();
            return response()->json(PlanResource::collection($plans)->response()->getData(true));
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }

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
     * @OA\Post(
     ** path="/api/plans",
     *   tags={"plan"},
     *   summary="create new plan",
     *   operationId="create new plan",
     *   security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="create new plan",
     *    @OA\JsonContent(
     *      @OA\Property(property="date", type="string", example="2021-12-01"),
     *      @OA\Property(property="exercises", type="string"),
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
    //create new plan
    public function store(Request $request)
    {
        try {

            $plan = Plan::create([
                'user_id' => auth('api')->user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            if ($plan) {
                //create exercise group
                $group = ExerciseGroup::create(['plan_id' => $plan->id,'date'=>$request->date]);
                foreach ($request->exercises as $exercise) {
                    Exercise::create([
                        'name' => $exercise['name'],
                        'group_id' => $group->id,
                        'sets' => $exercise['sets']
                    ]);
                }
            }


            return response()->json(['message' => 'Plan created', 'status' => '200 success'], 200);
        } catch
        (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }

    }

    /**
     * @OA\Get(
     * path="/api/plans/{plan_id}",
     * summary="show plan by id",
     * description="show plan by id",
     * tags={"plan"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="plan_id",
     * in="path",
     * required=true,
     * description="show plan by id",
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
            $plan = Plan::findOrFail($id);
            return response()->json(PlanResource::make($plan));
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
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
     * @OA\Put(
     * path="/api/plans/{plan_id}",
     * summary="edit plan",
     * description="edit plan",
     * tags={"plan"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="plan_id",
     * in="path",
     * required=true,
     * description="plan_id",
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="create new plan",
     *    @OA\JsonContent(
     *       required={"name","description","plandate","exercises"},
     *       @OA\Property(property="exercises", type="string"),
     *    ),
     * ),
     * @OA\Response(
     *      response=200,
     *       description="Success",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     * @OA\Response(
     *      response=201,
     *       description="Created",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     * @OA\Response(
     *      response=401,
     *       description="Unauthenticated"
     *   ),
     * @OA\Response(
     *      response=400,
     *      description="Bad Request"
     *   ),
     * @OA\Response(
     *      response=404,
     *      description="not found"
     *   ),
     * @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *)
     */
    public function update(Request $request, $id)
    {

        try {
            $plan = Plan::findOrFail($id);
            //find group exercises
            $group = ExerciseGroup::where('plan_id', $id)->first();
            //insert new exercises
            if ($group) {
                //delete previous exercises
                DB::table('exercises')->where('group_id', $group->id)->delete();
                //insert new exercises
                foreach ($request->exercises as $exercise) {
                    Exercise::create([
                        'name' => $exercise['name'],
                        'sets' => $exercise['sets'],
                        'group_id' => $group->id
                    ]);
                }

            }
            //if player update his/her plan
            if ($plan->trainer_id) {
                if (auth('api')->user()->id == $plan->user_id) {
                    //send notification to trainer
                    $receiver_id = $plan->trainer_id;
                    //from player
                    $player_name = $plan->user->name;
                    $message = $player_name . ' has updated his/her plan for the day : ' .$request->date.',  click to view details.';
                    $notification = Notification::create([
                        'title' => 'Plan Updated',
                        'text' => $message,
                        'user_id' => $receiver_id,
                        'notification_type' => 'Plan Updated'
                    ]);
                    event(new NotificationCreatedEvent($notification, $receiver_id));
                } elseif (auth('api')->user()->id == $plan->trainer_id) {
                    //send notification to player
                    $receiver_id = $plan->user_id;
                    //from trainer
                    $trainer_name = $plan->trainer->name;
                    $message = $trainer_name . ' has updated your plan for the day : ' .$request->date.',  click to view details.';
                    $notification = Notification::create([
                        'title' => 'Plan Updated',
                        'text' => $message,
                        'user_id' => $receiver_id,
                        'notification_type' => 'Plan Updated'
                    ]);
                    event(new NotificationCreatedEvent($notification, $receiver_id));
                }
            }

            return response()->json(['message' => 'Plan updated', 'status' => '200 success'], 200);
        } catch
        (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/plans/{plan_id}",
     * summary="delete plan by id",
     * description="delete plan by id",
     * tags={"plan"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="plan_id",
     * in="path",
     * required=true,
     * description="delete plan by id",
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
    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->delete();
            return response()->json(['message' => 'Plan deleted', 'status' => "200 success"]);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
}
