<?php

namespace App\Http\Resources;

use App\Models\Categories;
use App\Models\post_category;
use App\Models\userInfo;
use Carbon\Carbon;
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
        $imgs = explode(', ', $this->imgs);

        $cat_posts = post_category::where('posts_id', '=', $this->id)->get();

        $categories_arr = [];
        foreach($cat_posts as $link){
            $categories_arr[] = Categories::find($link->categories_id);
        }

        return [
            'id' => $this->id,
            'body' => $this->body,
            'categories' => $categories_arr,
            'imgs' => $imgs,
            'time' => Carbon::parse($this->created_at)->format('d.m.Y'),
            'user_name' => $user[0]->name,
            'user_avatar' => $user[0]->avatar,
        ];
    }
}
