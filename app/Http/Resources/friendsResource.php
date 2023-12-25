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


        $friend = userInfo::where('users_id', '=', $this->friend_id)->get();

                return [
                    'friend_id' => $this->friend_id,
                    'friend_name' => $friend[0]->name,
                    'friend_avatar' => $friend[0]->avatar
                ];
    }
}
    