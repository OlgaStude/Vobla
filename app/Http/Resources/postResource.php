<?php

namespace App\Http\Resources;

use App\Models\userInfo;
use Illuminate\Http\Resources\Json\JsonResource;

class postResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $user = userInfo::where('users_id', '=', $this->users_id)->get();

        return [
            'id' => $this->id,
            'body' => $this->body,
            'category_name' => $this->category_name,
            'time' => $this->created_at,
            'user_name' => $user[0]->name,
            'user_avatar' => $user[0]->avatar,
        ];
    }
}
