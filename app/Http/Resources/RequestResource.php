<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class RequestResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'body'=>$this->body,
            'request_type'=>$this->request_type,
            'trainer'=>UserResource::make($this->trainer),
            'player'=>UserResource::make($this->player),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
