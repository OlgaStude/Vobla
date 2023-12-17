<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\postController;
use App\Http\Resources\categoryResource;
use App\Models\Categories;
use App\Models\userInfo;
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


Route::post('/makepost', [postController::class, 'makePost']);



Route::get('/categories', function () {
    $categories = Categories::all();
    return categoryResource::collection($categories);
});
Route::get('/userinfo', function () {
    $user = userInfo::where('users_id', '=', Auth::user()->id)->get();
    return $user[0];
});