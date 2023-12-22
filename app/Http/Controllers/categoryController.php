<?php

namespace App\Http\Controllers;

use App\Http\Requests\categoryRequest;
use App\Models\Categories;
use App\Models\Post;
use App\Models\post_category;
use App\Models\UsersCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class categoryController extends Controller
{
    
    public function addCategory(Request $req){

        $category_id = Categories::where('name', '=', $req->name)->get();
        UsersCategories::create(['users_id' => Auth::user()->id, 'categories_id' => $category_id[0]->id]);

    }


    public function removeCategory(Request $req){

        $category_id = Categories::where('name', '=', $req->name)->get();
        UsersCategories::where([
                ['users_id', '=', Auth::user()->id], 
                ['categories_id', '=', $category_id[0]->id]
            ]
        )->delete();

    }


    public function createCategory(categoryRequest $req){

        Categories::create(['name' => $req->name]);
        return response()->json(['status' => 200, 'message' => 'Категория была добавлена']);

    }


    public function updateCategory(categoryRequest $req){

        Categories::where("name", $req->old_name)->update(["name" => $req->name]);
        Post::where("category_name", $req->old_name)->update(["category_name" => $req->name]);
        return response()->json(['status' => 200, 'message' => 'Категория была измененна']);

    }

    public function deleteCategory(categoryRequest $req){

        $category = Categories::where("name", $req->name)->get();
        $links = post_category::where('categories_id', '=', $category[0]->id)->get();
        post_category::where('categories_id', '=', $category[0]->id)->delete();
        $category = Categories::where("name", $req->name)->delete();
        foreach($links as $link){
            Post::where("id", $link->posts_id)->delete();
        }
        return response()->json(['status' => 200, 'message' => 'Категория была удалена']);

    }
    
    
}
