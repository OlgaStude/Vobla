<?php

namespace App\Http\Resources;

use App\Models\userInfo;
use App\Models\UsersCategories;
use Illuminate\Http\Resources\Json\JsonResource;

class postDashResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $exists = UsersCategories::where([
            ['users_id', '=', $this->users_id],
            ['categories_id', '=', $this->categories_id]
        ])->exists();


        if($exists){
            $user = userInfo::where('users_id', '=', $this->users_id)->get();
            $imgs = explode(', ', $this->imgs);

            return [
                'id' => $this->id,
                'body' => $this->body,
                'category_name' => $this->category_name,
                'imgs' => $imgs,
                'time' => $this->created_at,
                'user_name' => $user[0]->name,
                'user_avatar' => $user[0]->avatar,
            ];
        }
    }
}
