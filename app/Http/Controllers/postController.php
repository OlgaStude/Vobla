<?php

namespace App\Http\Controllers;

use App\Http\Requests\postRequest;
use App\Models\Categories;
use App\Models\Post;
use App\Models\post_category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Psy\Readline\Hoa\Console;

class postController extends Controller
{
    
    public function makePost(postRequest $req){

        $category = Categories::where('name', '=', $req->category_name)->get();
        $body = $req->body;
        $names =[];
        if($req->imgs != []){
            foreach($req->imgs as $img){
                $img->store('public/posts_imgs');
                $names[] = "src=\"/storage/posts_imgs/".$img->hashName().'" ';
            }
            $body = preg_replace_array('(src="data.+" )', $names, $req->body);
           
        }
        
        

        $post = Post::create(['users_id' => Auth::user()->id, 'body' => $body, 'imgs' => implode(', ', $names)]);
        foreach($req->categories as $category){
            $category = Categories::where('name', '=', $category)->get();
            post_category::create(['posts_id' => $post->id, 'categories_id' => $category[0]->id]);
        }
    }

    public function postDelete(Request $req){

        post_category::where('posts_id', '=', $req->id)->delete();

        Post::where("id", $req->id)->delete();

    }


    public function postUpdate(postRequest $req){


        if($req->old_imgs != false || $req->old_imgs != ''){
            $old_imgs = $req->old_imgs;  
        }else{
            $old_imgs = [];
        }
        $body = $req->body;
        $names =[];
        if($req->imgs != []){
            foreach($req->imgs as $img){
                $img->store('public/posts_imgs');
                $names[] = "src=\"/storage/posts_imgs/".$img->hashName().'" ';
                $old_imgs[] = "src=\"/storage/posts_imgs/".$img->hashName().'" ';
            }
            $body = preg_replace_array('(src="data.+" )', $names, $req->body);
        }
        Post::where("id", $req->id)->update(['body' => $body, 'imgs' => implode(', ', $old_imgs)]);


        $post = Post::where("id", $req->id)->get();

            $links = post_category::where('posts_id', '=', $req->id)->get();
            foreach($links as $link){
                $category = Categories::find($link->categories_id);
                if(!in_array($category->name, $req->categories)){
                post_category::where([
                    ['posts_id', '=', $req->id],
                    ['categories_id', '=', $category->id]
                ])->delete();
                }
            }

            foreach($req->categories as $category){
                $category = Categories::where('name', '=', $category)->get();
                post_category::create(['posts_id' => $post[0]->id, 'categories_id' => $category[0]->id]);
            }
    
    }

}
