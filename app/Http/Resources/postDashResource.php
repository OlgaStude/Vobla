<?php

namespace App\Http\Resources;

use App\Models\Categories;
use App\Models\post_category;
use App\Models\userInfo;
use App\Models\UsersCategories;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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

        $cat_posts = post_category::where('posts_id', '=', $this->id)->get();
        
        $counter = 0;
        foreach($cat_posts as $check){
            $exists = UsersCategories::where([
                ['users_id', '=', Auth::user()->id],
                ['categories_id', '=', $check->categories_id]
            ])->exists();
            if($exists){
                $counter++;
            }
        }


        if($counter > 0){
            $user = userInfo::where('users_id', '=', $this->users_id)->get();


            return [
                'id' => $this->id,
                'body' => $this->body,
                'time' => Carbon::parse($this->created_at)->format('d.m.Y'),
                'user_name' => $user[0]->name,
                'user_avatar' => $user[0]->avatar,
                'user_id' => $user[0]->id,
            ];
        }
    }
}
