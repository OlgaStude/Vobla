<?php

namespace App\Http\Resources;

use App\Models\UsersCategories;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class categoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if(Auth::check()){
            $exists = UsersCategories::where([
            ['users_id', '=', Auth::user()->id], 
            ['categories_id', '=', $this->id]
            ]
            )->exists();
    
            $users = $exists;
        }else{
            $users = false;
        }


        return [
            'name' => $this->name,
            'id' => $this->id,
            'users' => $users
        ];
    }
}
