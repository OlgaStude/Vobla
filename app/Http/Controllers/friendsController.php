<?php

namespace App\Http\Controllers;

use App\Models\friendsRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class friendsController extends Controller
{
    
    public function sendRequest(Request $req){

        friendsRequest::create(['reciever_id' => $req->id, 'sender_id' => Auth::user()->id]);

    }

}
