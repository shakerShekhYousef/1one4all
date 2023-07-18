<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'notification_type'=>$this->notification_type,
            'text'=>$this->text,
            'user'=>UserResource::make($this->user),
            'sender'=>UserResource::make($this->sender),
            'request'=>RequestResource::make($this->request),
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at
        ];
    }
}
