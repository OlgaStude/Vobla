<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\loginReques;
use App\Http\Requests\passwordRequest;
use App\Http\Requests\registerRequest;
use App\Http\Requests\registrationRequest;
use App\Http\Requests\updateRequest;
use App\Models\Categories;
use App\Models\Chat_messages;
use App\Models\Friends;
use App\Models\friendsRequest;
use App\Models\Post;
use App\Models\post_category;
use App\Models\User;
use App\Models\userInfo;
use App\Models\UsersCategories;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{

    public function register(registerRequest $req)
    {
        if($req->hasFile('avatar')){
            $req->file('avatar')->store('public/profile_pics');
            $pfp_name = $req->file('avatar')->hashName();
        }else{
            $pfp_name = 'default_avatar.png';
        }

        $user = User::create(['password' => Hash::make($req->password), 'login' => $req->login, 'is_admin' => 0]);
        if($req->categories != []){
            
            foreach($req->categories as $category){
                $category_id = Categories::where('name', '=', $category)->get();
                UsersCategories::create(['users_id' => $user->id, 'categories_id' => $category_id[0]->id, 'is_admin' => 0]);
            }
        }
        userInfo::create(['users_id' => $user->id, 'name' => $req->name, 'avatar' => $pfp_name, 'email' => $req->email]);

        if ($user) {
            Auth::login($user);
            $user_id = User::where("login", $user['login'])->get();
            return response()->json(['status' => 200, 'message' => 'user is registreted!', 'user_id' => $user_id[0]->id]);
        }

        return response()->json(['status' => 422, 'message' => 'user is failed to be registreted!']);
    }


    public function login(loginReques $req)
    {

        $user = [
            'login' => $req->login,
            'password' => $req->password
        ];

        if (Auth::attempt($user)) {
            $user_id = User::where("login", $user['login'])->get();
            return response()->json(['status' => 200, 'message' => 'user logged in', 'user_id' => $user_id[0]->id]);
        }

        return response()->json(['status' => 400, 'message' => 'Неверный логин или пароль']);
    }


     public function logout()
    {
        try {
            Auth::logout();
            return response()->json(['status' => 200, 'message' => 'user is logged out!']);
        } catch (QueryException $e) {
            return response()->json(['status' => 422, 'message' => $e]);
        }
    }


    public function updateuser(updateRequest $req){

        userInfo::where("id", Auth::user()->id)->update(["name" => $req->name]);
        if($req->login != 'nonewlogin'){
            User::where("id", Auth::user()->id)->update(["login" => $req->login]);
        }
        if($req->hasFile('avatar')){
            $req->file('avatar')->store('public/profile_pics');
            $material_name = $req->file('avatar')->hashName();

            $check = userInfo::where("id", '=', Auth::user()->id)->get();

            if($check[0]->avatar != 'default_avatar.png'){
                $avatar = userInfo::where("users_id", '=', Auth::user()->id)->get();
                Storage::delete("public/profile_pics/".$avatar[0]->avatar);
            }


            userInfo::where("id", Auth::user()->id)->update(["avatar" => $material_name]);

        }


    }


    public function changepassword(passwordRequest $req){

        $check = Hash::check($req->password, User::find(Auth::user()->id)->password);

        if(!$check){
            return response()->json(['status' => 400, 'message' => 'Неверный пароль']);
        }
        User::where("id", Auth::user()->id)->update(["password" => Hash::make($req->new_password)]);


    }


    public function deleteUser(){

        Chat_messages::where('sender_id', '=', Auth::user()->id)->orWhere('reciewer_id', '=', Auth::user()->id)->delete();
        Friends::where('user_id', '=', Auth::user()->id)->orWhere('friend_id', '=', Auth::user()->id)->delete();
        friendsRequest::where('reciever_id', '=', Auth::user()->id)->orWhere('sender_id', '=', Auth::user()->id)->delete();
        $posts = Post::where('users_id', '=', Auth::user()->id)->get();
        foreach($posts as $post){
            post_category::where('posts_id', '=', $post->id)->delete();
        }
        Post::where('users_id', '=', Auth::user()->id)->delete();
        UsersCategories::where('users_id', '=', Auth::user()->id)->delete();
        userInfo::where('users_id', '=', Auth::user()->id)->delete();
        User::where('id', '=', Auth::user()->id)->delete();
    }

}
