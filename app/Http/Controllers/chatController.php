<?php

namespace App\Http\Controllers;

use App\Http\Requests\chatMessageRequest;
use App\Http\Resources\chatMessageResource;
use App\Models\Chat_messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class chatController extends Controller
{
    public function create_message(chatMessageRequest $req){

        $message = Chat_messages::create(['sender_id' => Auth::user()->id, 'reciewer_id' => $req->receiver_id, 'message' => $req->message]);

        if($message){
           
            return response()->json(['status' => 200, 'message' => 'message is created!']);
        }

        return response()->json(['status' => 422, 'message' => 'message is not created!']);

    }


    public function getChatMessages($id){

    
            if($id != Auth::user()->id){
                $messages = Chat_messages::where([
                    ['sender_id', '=', Auth::user()->id],
                    ['reciewer_id', '=', $id],
                ])->orWhere([
                    ['reciewer_id', '=', Auth::user()->id],
                    ['sender_id', '=', $id],
                ])->orderBy('id', 'asc')->get();
        
                return chatMessageResource::collection($messages);
            }
    
            return 'false';

        

    }
}
