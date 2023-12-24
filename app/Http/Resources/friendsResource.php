<?php

namespace App\Http\Resources;

use App\Models\userInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class friendsResource extends JsonResource
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
            'friend_id' => $this->id,
            'friend_name' => $this->name,
            'friend_avatar' => $this->avatar
        ];
    }
}
    