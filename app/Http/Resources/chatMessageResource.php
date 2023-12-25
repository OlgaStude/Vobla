<?php

namespace App\Http\Resources;

use App\Models\userInfo;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class chatMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if($this->reciewer_id == Auth::user()->id){
            $test = true;

            $user = userInfo::where('id', '=', $this->sender_id)->get();

            return [
                'id' => $this->id,
                'messager_id' => $this->sender_id,
                'messager_name' => $user[0]->name,
                'messager_pfp' => $user[0]->avatar,
                'is_received_message' => $test,
                'time' => Carbon::parse($this->created_at)->format('d.m.Y'),
                'text' => $this->message
            ];
        } else {
            $test = false;
            $user = userInfo::where('id', '=', $this->sender_id)->get();
            return [
                'id' => $this->id,
                'messager_id' => $this->sender_id,
                'messager_name' => $user[0]->name,
                'messager_pfp' => $user[0]->avatar,
                'is_received_message' => $test,
                'time' => Carbon::parse($this->created_at)->format('d.m.Y'),
                'text' => $this->message
            ];
        }
    }
}
