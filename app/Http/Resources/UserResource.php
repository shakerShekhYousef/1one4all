<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Certificate;
use App\Models\Level;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $certificates=Certificate::where('user_id',$this->id)->get();
        if($this->subcategory){
            $category=Category::where('id',$this->subcategory->category_id)->pluck('name')->first();
        }else{
            $category=null;
        }
        $level=Level::where('id',$this->level_id)->first();
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'mobile'=>$this->mobile,
            'age'=>$this->age,
            'role'=>$this->role->name,
            'category'=>$category,
            'subcategory'=>$this->subcategory,
            'bio'=>$this->bio,
            'country_code'=>$this->country_code,
            'level'=>$level,
            'profile_picture'=>$this->profile_pic ? '/public/storage/users/'.$this->profile_pic : '/public/storage/users/user.png',
            'certificates'=>CertificateResource::collection($certificates),
            'approved'=>$this->approved,
            'plans'=>$this->plans,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
