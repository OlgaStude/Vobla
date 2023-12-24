<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\chatController;
use App\Http\Controllers\friendsController;
use App\Http\Controllers\postController;
use App\Http\Resources\categoryResource;
use App\Http\Resources\friendsRequestResource;
use App\Http\Resources\friendsResource;
use App\Http\Resources\postDashResource;
use App\Http\Resources\postResource;
use App\Http\Resources\usersChatsResource;
use App\Models\Categories;
use App\Models\Friends;
use App\Models\friendsRequest;
use App\Models\Post;
use App\Models\userInfo;
use App\Models\UsersCategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/updateuser', [UserController::class, 'updateuser']);
Route::post('/changepassword', [UserController::class, 'changepassword']);


Route::post('/useraddcategory', [categoryController::class, 'addCategory']);
Route::post('/userremovecategory', [categoryController::class, 'removeCategory']);
Route::post('/addcategory', [categoryController::class, 'createCategory']);
Route::post('/updatecategory', [categoryController::class, 'updateCategory']);
Route::post('/deletecategory', [categoryController::class, 'deleteCategory']);


Route::post('/makepost', [postController::class, 'makePost']);
Route::post('/deletepost', [postController::class, 'postDelete']);
Route::post('/updatepost', [postController::class, 'postUpdate']);
Route::post('/getpostsuserpage', function(Request $req){
    $posts = Post::where('users_id', '=', $req->id)->orderBy('id', 'desc')->get();
    return postResource::collection($posts);
});
Route::get('/getpostsdash', function(){
    $posts = Post::orderBy('id', 'desc')->get();
    return postDashResource::collection($posts);
});
Route::get('/getpostsall', function(){
    $posts = Post::orderBy('id', 'desc')->get();
    return postResource::collection($posts);
});


Route::post('/sendrequest', [friendsController::class, 'sendRequest']);
Route::post('/respondtorequest', [friendsController::class, 'responseToRequest']);
Route::post('/deletefriend', [friendsController::class, 'friendDelete']);


Route::post('/sendmessage', [chatController::class, 'create_message']);
Route::get('/getmessages/{id}', [chatController::class, 'getChatMessages']);


Route::get('/categories', function () {
    $categories = Categories::all();
    return categoryResource::collection($categories);
});
Route::get('/userinfo', function () {
    $user = userInfo::where('users_id', '=', Auth::user()->id)->get();
    return $user[0];
});
Route::post('/otheruserinfo', function (Request $req) {
    $user = userInfo::where('users_id', '=', $req->id)->get();
    $user[0]->categories = categoryResource::collection(Categories::all());
    return $user[0];
});
Route::get('/friendsrequests', function () {
    $request = friendsRequest::where('reciever_id', '=', Auth::user()->id)->get();
    return friendsRequestResource::collection($request);
});
Route::post('/friendcheck', function (Request $req) {
    $exists = Friends::where([['user_id', '=', Auth::user()->id], ['friend_id', '=', $req->id]])->exists();
    return $exists;
});
Route::get('/getfriends', function () {
    $users = userInfo::where('users_id', '<>', Auth::user()->id)->get();
    return friendsResource::collection($users);
});
Route::get('/getchats', function () {
    $friends = Friends::where('user_id', '=', Auth::user()->id)->get();
    return usersChatsResource::collection($friends);
});