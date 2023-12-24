<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\userInfo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class friendsRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if($this->reciever_id == Auth::user()->id){

            $user = userInfo::where('users_id', '=', $this->sender_id)->get();
            return [
                'user_id' => $this->sender_id,
                'user_name' => $user[0]->name,
                'user_avatar' => $user[0]->avatar
            ];
        }
    }
}
