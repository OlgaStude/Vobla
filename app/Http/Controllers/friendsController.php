<?php

namespace App\Http\Controllers;

use App\Models\Chat_messages;
use App\Models\Friends;
use App\Models\friendsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class friendsController extends Controller
{
    
    public function sendRequest(Request $req){

        $exists = friendsRequest::where([['reciever_id', '=', $req->id], ['sender_id', '=', Auth::user()->id]])->exists();

        if(!$exists){
            friendsRequest::create(['reciever_id' => $req->id, 'sender_id' => Auth::user()->id]);
        }

    }


    public function responseToRequest(Request $req){

        friendsRequest::where([['reciever_id', '=', $req->id], ['sender_id', '=', Auth::user()->id]])->delete();
        friendsRequest::where([['reciever_id', '=', Auth::user()->id], ['sender_id', '=', $req->id]])->delete();

        if($req->accepted){
            Friends::create(['user_id' => $req->id, 'friend_id' => Auth::user()->id]);
            Friends::create(['user_id' => Auth::user()->id, 'friend_id' => $req->id]);
        }

    }


    public function friendDelete(Request $req){
       
        Friends::where([
            ['user_id', $req->id], 
            ['friend_id', Auth::user()->id]
        ])->delete();
        Friends::where([['user_id', Auth::user()->id], ['friend_id', $req->id]])->delete();

        Chat_messages::where([
            ['sender_id', '=', Auth::user()->id],
            ['reciewer_id', '=', $req->id],
        ])->orWhere([
            ['reciewer_id', '=', Auth::user()->id],
            ['sender_id', '=', $req->id],
        ])->delete();
    }

}
