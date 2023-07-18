<?php

namespace App\Http\Resources;

use App\Models\Exercise;
use App\Models\Plan;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciseGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $plan=Plan::where('id',$this->plan_id)->first();
        $exerices=Exercise::where('group_id',$this->id)->get();
        return [
            'id'=>$this->id,
            'description'=>$this->description,
            'plan'=>PlanResource::make($plan),
            'exercises'=>$exerices,
        ];
    }
}
