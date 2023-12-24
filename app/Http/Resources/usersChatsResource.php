<?php

namespace App\Http\Resources;

use App\Models\Chat_messages;
use App\Models\userInfo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class usersChatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $exists = Chat_messages::where([
            ['sender_id', '=', Auth::user()->id],
            ['reciewer_id', '=', $this->friend_id],
        ])->orWhere([
            ['reciewer_id', '=', Auth::user()->id],
            ['sender_id', '=', $this->friend_id],
        ])->exists();

            if($exists){
                $friend = userInfo::where('users_id', '=', $this->friend_id)->get();

                return [
                    'friend_id' => $this->friend_id,
                    'friend_name' => $friend[0]->name,
                    'friend_avatar' => $friend[0]->avatar
                ];
            }

        
    }
}
