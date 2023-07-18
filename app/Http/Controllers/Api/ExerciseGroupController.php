<?php

namespace App\Http\Controllers\Api;

use App\Events\NotificationCreatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseGroupResource;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Models\ExerciseGroup;
use App\Models\Notification;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ExerciseGroupController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/exercise_group",
     * summary="get group / get group for a specific date.",
     * description="get group / get group for a specific date.",
     * tags={"exercise group"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="date",
     * in="query",
     * required=false,
     * description="search by month(01,02,03,04....ex) example:2021-10",
     * ),
     * @OA\Parameter(
     * name="user_id",
     * in="query",
     * required=false,
     * description="search by user_id",
     * ),
     * @OA\Parameter(
     * name="plan_id",
     * in="query",
     * required=false,
     * description="search by plan_id",
     * ),
     * @OA\Parameter(
     * name="trainer_id",
     * in="query",
     * required=false,
     * description="search by trainer_id",
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
            $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : auth('api')->user()->id;
            $plan_id = isset($_GET['plan_id']) ? $_GET['plan_id'] : null;
            $trainer_id = isset($_GET['trainer_id']) ? $_GET['trainer_id'] : null;
            //response
            $query_plans=Plan::where('user_id','like', '%' .$user_id)
                ->where('id','like', '%' .$plan_id);
            if($trainer_id==null){
                $plans=clone $query_plans->select('id')->get();
            }else{
                $plans=clone $query_plans->where('trainer_id',$trainer_id)->select('id')->get();
            }
            $query=DB::table('exercise_groups')->whereIn('plan_id',$plans);
            if ($date !== null)
                $groups = clone $query->where('date', 'like', '%' . $date . '%')->get();
            else
                $groups = clone $query->get();
            return response()->json(ExerciseGroupResource::collection($groups)->response()->getData(true));
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

    }

    /**
     * @OA\Post(
     * path="/api/exercise_group",
     * summary="create new exercise group",
     * description="create new exercise group",
     * tags={"exercise group"},
     * security={{ "apiAuth": {} }},
     * @OA\RequestBody(
     *    required=true,
     *    description="create exercise group",
     *    @OA\MediaType(
     *      mediaType="multipart/form-data",
     *    @OA\Schema(
     *       @OA\Property(property="description", type="string", example="test"),
     *       @OA\Property(property="date", type="string", example="2021-12-4"),
     *       @OA\Property(property="plan_id", type="number", description="plan's id"),
     *       @OA\Property(property="exercises", type="string",example="[{'name':'test','sets':'20 20'}]"),
     *    )
     *    )
     *  ),
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
    //create group exercise
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'plan_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
        try {
            //get plan
            $plan=Plan::where('id',$request->plan_id)->first();
            //create new group
            $group = ExerciseGroup::create([
                'date' => $request->date,
                'description' => $request->description,
                'plan_id' => $request->plan_id
            ]);
            //create exercises for group
            if ($group) {
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
                    $message = $player_name . ' has updated his/her plan for the day : ' .$request->date.', click to view details.';
                    $notification = Notification::create([
                        'title' => 'Plan Updated',
                        'text' => $message,
                        'user_id' => $receiver_id,
                        'sender_id'=>auth('api')->user()->id,
                        'notification_type' => 'Plan Updated'
                    ]);
                    event(new NotificationCreatedEvent($notification, $receiver_id));
                } elseif (auth('api')->user()->id == $plan->trainer_id) {
                    //send notification to player
                    $receiver_id = $plan->user_id;
                    //from trainer
                    $trainer_name = $plan->trainer->name;
                    $message = $trainer_name . ' has updated your plan for the day : ' .$request->date.', click to view details.';
                    $notification = Notification::create([
                        'title' => 'Plan Updated',
                        'text' => $message,
                        'user_id' => $receiver_id,
                        'sender_id'=>auth('api')->user()->id,
                        'notification_type' => 'Plan Updated'
                    ]);
                    event(new NotificationCreatedEvent($notification, $receiver_id));
                }
            }
            return response()->json(['message' => 'Exercise group created', 'status' => '200 success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
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
     * @OA\Put(
     * path="/api/exercise_group/{group_id}",
     * summary="update exercise group",
     * description="update exercise group",
     * tags={"exercise group"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="group_id",
     * in="path",
     * required=true,
     * description="group_id",
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="update exercise group",
     *    @OA\JsonContent(
     *       @OA\Property(property="description", type="string", example="test"),
     *       @OA\Property(property="date", type="string", example="2021-12-4"),
     *       @OA\Property(property="plan_id", type="number", description="plan's id"),
     *       @OA\Property(property="exercises", type="string",
     *     example="[{'name':'test','sets':'20 20'}]"),
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
    //update group exercise
    public function update(Request $request, $id)
    {

        //get group exercise
        $group = ExerciseGroup::where('id', $id)->first();
        //get plan
        $plan=Plan::where('id',$request->plan_id)->first();
        try {
            $group->update([
                'date' => $request->date,
                'description' => $request->description,
                'plan_id' => $request->plan_id
            ]);
            //create exercises for group
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
                        'sender_id'=>auth('api')->user()->id,
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
                        'sender_id'=>auth('api')->user()->id,
                        'notification_type' => 'Plan Updated'
                    ]);
                    event(new NotificationCreatedEvent($notification, $receiver_id));
                }
            }
            return response()->json(['message' => 'Exercise group updated', 'status' => '200 success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/exercise_group/{group_id}",
     * summary="delete exercise group by id",
     * description="delete exercise group by id",
     * tags={"exercise group"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="group_id",
     * in="path",
     * required=true,
     * description="delete group exercise by id",
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
            $group = ExerciseGroup::findOrFail($id);
            $group->delete();
            return response()->json(['message' => 'Group deleted', 'status' => "200 success"]);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
}
