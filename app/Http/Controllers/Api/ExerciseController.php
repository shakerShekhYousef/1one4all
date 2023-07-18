<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExerciseResource;
use App\Models\Exercise;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExerciseController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/exercises",
     * summary="get exercise / get exercise for a specific date.",
     * description="get exercise / get exercise for a specific date.",
     * tags={"exercise"},
     * security={{ "apiAuth": {} }},
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
            //exercise query
            $query = DB::table('exercises')->whereIn('group_id', function ($query) {
                $query->from('exercise_groups')
                    ->join('plans', 'exercise_groups.plan_id', '=', 'plans.id')
                    ->where('user_id', auth('api')->user()->id)
                     ->select('exercise_groups.id')->get();
            })->get();
            //response
            if ($date !== null)
                $exercises = clone $query->where('date', 'like', '%' . $date . '%')->get();
            else
                $exercises = clone $query->get();
            return response()->json(ExerciseResource::collection($exercises)->response()->getData(true));
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
     * @OA\Put(
     * path="/api/exercises/{exercise_id}",
     * summary="edit exercise",
     * description="edit exercise",
     * tags={"exercise"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="exercise_id",
     * in="path",
     * required=true,
     * description="exercise_id",
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="create new plan",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="test"),
     *       @OA\Property(property="sets", type="string", example="sets"),
     *       @OA\Property(property="date", type="string", example="2021-12-4"),
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
    //update exercise
    public function update(Request $request, $id)
    {
        try {
            //get exercise
            $exercise = Exercise::where('id', $id)->first();
            //update info
            $exercise->update([
                'name' => $request->name,
                'date' => $request->date,
                'sets' => $request->sets,
            ]);
            return response()->json(['message' => 'Exercise updated', 'status' => '200 success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
    /**
     * @OA\Post(
     * path="/api/exercises/update",
     * summary="edit exercise by date",
     * description="edit exercise by date",
     * tags={"exercise"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="date",
     * in="query",
     * required=true,
     * description="date",
     * ),
     * @OA\RequestBody(
     *    required=true,
     *    description="create new plan",
     *    @OA\JsonContent(
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
    //update exercise by date
    public function updateDate(Request $request)
    {
        $date = $_GET['date'];
        try {
            //get exercises by date and delete it
            $exercises = Exercise::where('date', 'like', '%' . $date . '%')->get();
            if ($exercises->count() > 0) {
                foreach ($exercises as $exercise) {
                    $exercise->delete();
                }
            } else {
                return response()->json(['message' => 'No exercises on this date', 'status' => '404 not found'], 404);
            }
            //update info
            foreach ($request->exercises as $exercise) {
                Exercise::create([
                    'name' => $exercise['name'],
                    'date' => $exercise['date'],
                    'sets' => $exercise['sets'],
                    'plan_id' => $exercise['plan_id']
                ]);
            }
            return response()->json(['message' => 'Exercises updated', 'status' => '200 success'], 200);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }

    /**
     * @OA\Delete(
     * path="/api/exercises/{exercise_id}",
     * summary="delete exercise by id",
     * description="delete exercise by id",
     * tags={"exercise"},
     * security={{ "apiAuth": {} }},
     * @OA\Parameter(
     * name="exercise_id",
     * in="path",
     * required=true,
     * description="delete exercise by id",
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
            $exercise = Exercise::findOrFail($id);
            $exercise->delete();
            return response()->json(['message' => 'Exercise deleted', 'status' => "200 success"]);
        } catch (\Throwable $th) {
            return response()->json(['errors' => $th->getMessage()], 400);
        }
    }
}
