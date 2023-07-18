<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{

    protected $addedexercisecount = 0;
    public function createplan(Request $request)
    {
        $request->validate(['name' => 'required']);
        try {
            $planexist = Plan::where('name', $request->name)->where('description', $request->description)->where('level', $request->level)->count();
            if ($planexist == 0) {
                $plan = Plan::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'level' => $request->level,
                    'plandate' => $request->plandate
                ]);
                if ($plan != null)
                    return response()->json(['success' => true, 'message' => 'Plan has been created successfuly']);
                else
                    return response()->json(['success' => false, 'message' => 'Error happen during create new plan']);
            } else {
                return response()->json(['success' => false, 'message' => 'This plan is already exist']);
            }
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    // name, plan_id, player_id
    public function addsingleexercise(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'plan_id' => 'required|exists:plans,id'
        ]);
        try {
            $exerciseexists = Exercise::where('name', $request->name)->where('plan_id', $request->plan_id)->count();
            if ($exerciseexists == 0) {
                $exercise = Exercise::create([
                    'name' => $request->name,
                    'plan_id' => $request->plan_id
                ]);
                // if player id is not null
                if ($request->player_id != null) {
                    $user = User::find($request->player_id);
                    if ($user == null)
                        return response()->json(['success' => false, 'message' => 'The selected player is not found!']);
                    UserPlan::create([
                        'user_id' => $request->player_id,
                        'plan_id' => $request->plan_id
                    ]);
                }
                $plan = Plan::find($request->plan_id);
                $plandata = [
                    'Plan_name' => $plan->name,
                    'Plan_description' => $plan->description,
                    'Plan_date' => $plan->plandate
                ];
                if ($exercise != null)
                    return response()->json(['success' => true, 'message' => ['Plane data: ' => $plandata, 'Exercise data:' => ['name' => $request->name]]]);
                else
                    return response()->json(['success' => false, 'message' => 'Error happen during create new exercise']);
            } else {
                return response()->json(['success' => false, 'message' => 'This exercise is already exist for this plan']);
            }
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function addexercise($exercise)
    {
        try {
            $exerciseexists = Exercise::where('name', $exercise['name'])->where('plan_id', $exercise['plan_id'])->count();
            if ($exerciseexists == 0) {
                $exercise0 = Exercise::create([
                    'name' => $exercise['name'],
                    'plan_id' => $exercise['plan_id']
                ]);
                if ($exercise0 != null) {
                    // increase added count 
                    $this->addedexercisecount++;

                    // if player id is not null assign player to plan
                    if ($exercise['player_id'] != null) {
                        $user = User::find($exercise['player_id']);
                        if ($user == null)
                            return response()->json(['success' => false, 'message' => 'The selected player is not found!']);

                        $isassigned = UserPlan::where('user_id', $exercise['player_id'])->where('plan_id', $exercise['plan_id'])->count();
                        if ($isassigned == 0) {
                            UserPlan::create([
                                'user_id' => $exercise['player_id'],
                                'plan_id' => $exercise['plan_id']
                            ]);
                        }
                    }
                    $plan = Plan::find($exercise['plan_id']);
                    $plandata = [
                        'Plan_name' => $plan->name,
                        'Plan_description' => $plan->description,
                        'Plan_date' => $plan->plandate
                    ];
                    if ($exercise != null)
                        return response()->json(['success' => true, 'message' => ['Plane data: ' => $plandata, 'Exercise data:' => ['name' => $exercise['name']]]]);
                    else
                        return response()->json(['success' => false, 'message' => 'Error happen during create new exercise']);
                }
            } else {
                return response()->json(['success' => false, 'message' => 'This exercise is already exist for this plan']);
            }
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function assigntrainertoplan(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:users,id',
            'plan_id' => 'required|exists:plans,id'
        ]);
        try {
            $isassigned = UserPlan::where('user_id', $request->player_id)->where('plan_id', $request->plan_id)->count();
            if ($isassigned == 0) {
                $userplan = UserPlan::create([
                    'user_id' => $request->player_id,
                    'plan_id' => $request->plan_id
                ]);
                if ($userplan != null)
                    return response()->json(['success' => true, 'message' => 'Assign trainer to plan success']);
                else
                    return response()->json(['success' => false, 'message' => 'Error happen during assigning trainer to plan']);
            } else {
                return response()->json(['success' => false, 'message' => 'This trainer is already assigned to this plan']);
            }
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    // plan_id, exercises, player_id
    public function addmultipleexercises($planid, Request $request)
    {
        try {
            $this->addedexercisecount = 0;
            $plan = Plan::find($planid);
            if ($plan == null)
                return response()->json(['success' => false, 'message' => 'This plan does not found!']);

            $request->exercises = json_decode($request->exercises);

            if ($request->exercises != null) {
                foreach ($request->exercises as $key => $exercise) {
                    $this->addexercise(['name' => $exercise->name, 'plan_id' => $planid, 'player_id' => $request->player_id]);
                }
            }

            if ($this->addedexercisecount > 0)
                return response()->json(['success' => true, 'message' => $this->addedexercisecount > 1 ? '[' . $this->addedexercisecount . '] Exercises  have been added successfully' : 'one Exercise  have been added successfully']);
            else
                return response()->json(['success' => false, 'message' => 'No exercises added!']);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function editmultipleexercises($planid, Request $request)
    {
        try {

            $plan = Plan::find($planid);
            if ($plan == null)
                return response()->json(['success' => false, 'message' => 'This plan does not found!']);

            // remove old exercises
            Exercise::where('plan_id', $planid)->delete();

            // change plan date if plandate not null
            if ($request->plandate != null) {
                $plan->plandate = $request->plandate;
                $plan->save();
            }

            // add new data
            $request->exercises = json_decode($request->exercises);
            if ($request->exercises != null) {
                foreach ($request->exercises as $key => $exercise) {
                    $this->addexercise(['name' => $exercise->name, 'plan_id' => $planid, 'player_id' => $request->player_id]);
                }
            }
            return response()->json(['success' => true, 'message' => 'Exercises have been updated successfully']);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function getexercisesinmonth($month)
    {
        try {
            $plans = Plan::with('exercises')->whereMonth('plandate', $month)->get();
            $data = [];
            foreach ($plans as $key => $plan) {
                $items = [];
                foreach ($plan->exercises as $key => $exercise) {
                    $items[] = [
                        'Id:' => $exercise->id,
                        'Exercise name:' => $exercise->name,
                    ];
                }
                $data[] = [
                    'Plan_name:' => $plan->name,
                    'Plan_date:' => $plan->plandate,
                    'Exercises:' => $items
                ];
            }
            if (!empty($data)) {
                return response()->json(['success' => true, 'message' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => 'No data found!']);
            }
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }

    public function editplan($id, Request $request)
    {
        try {
            $plan = Plan::find($id);
            if ($plan == null)
                return response()->json(['success' => false, 'message' => 'Plan not found!']);

            $request->name != null ? $plan->name = $request->name : null;
            $request->description != null ? $plan->description = $request->description : null;
            $request->level != null ? $plan->level = $request->level : null;
            $request->plandate != null ? $plan->plandate = $request->plandate : null;
            $plan->save();
            return response()->json(['success' => true, 'message' => 'Plan has been upadated successfully']);
        } catch (\Throwable $th) {
            Log::channel('1one4allcustomelog')->info($th->getMessage());
        }
    }
}
