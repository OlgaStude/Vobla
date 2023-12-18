<?php

namespace App\Http\Controllers;

use App\Http\Requests\postRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

class postController extends Controller
{
    
    public function makePost(postRequest $req){
        // return $req->body;

        $body = $req->body;
        $names =[];
        if($req->imgs != []){
            foreach($req->imgs as $img){
                $img->store('public/posts_imgs');
                $names[] = "src=\"/storage/posts_imgs/".$img->hashName().'" ';
            }
            $body = preg_replace_array('(src="data.+?=")', $names, $req->body);
        }

        Post::create(['users_id' => Auth::user()->id, 'category_name' => $req->category_name, 'body' => $body, 'imgs' => implode(', ', $names)]);

    }

    public function postDelete(Request $req){

        Post::where("id", $req->id)->delete();

    }

}
