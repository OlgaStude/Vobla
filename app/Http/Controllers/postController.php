<?php

namespace App\Http\Controllers;

use App\Http\Requests\postRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class postController extends Controller
{
    
    public function makePost(postRequest $req){
        // return $req->body;

        if($req->imgs != []){
            $names =[];
            foreach($req->imgs as $img){
                $names[] = "src=".$img->hashName().'"';
                
            }
            $replaced = preg_replace_array('(src=".+?=")', $names, $req->body);
            return $replaced;
        }

        

    }

}
