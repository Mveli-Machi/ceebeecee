<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\AppuserController;
use App\Http\Controllers\API\SermonsController;
use App\Http\Controllers\API\FrontEndController;


//Route::post('register', [AppuserController::class, 'store']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('view-article', [FrontEndController::class, 'news']);

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::middleware(['auth:sanctum', 'IsApiAdmin'])->group(function(){

    Route::get('/checkingAuthenticated', function(){
        return response()->json(['message'=>'You are in', 'status'=>200], 200);
    });

    //users
    Route::post('store-user', [AppuserController::class, 'store']);
    Route::get('view-users', [AppuserController::class, 'index']);
    Route::get('edit-user/{id}', [AppuserController::class, 'edit']);
    Route::delete('delete-user/{id}', [AppuserController::class, 'destroy']); 
    Route::post('update-user/{id}', [AppuserController::class, 'update']);

    //news
    Route::post('store-news', [NewsController::class, 'store']);
    Route::get('view-news', [NewsController::class, 'index']);
    Route::get('edit-news/{id}', [NewsController::class, 'edit']);
    Route::delete('delete-article/{id}', [NewsController::class, 'destroy']); 
    Route::post('update-news/{id}', [NewsController::class, 'update']);

    //sermons
    Route::post('store-sermon', [SermonsController::class, 'store']);
    Route::get('view-sermons', [SermonsController::class, 'index']);
    Route::get('edit-sermon/{id}', [SermonsController::class, 'edit']);
    Route::delete('delete-sermon/{id}', [SermonsController::class, 'destroy']); 
    Route::post('update-sermon/{id}', [SermonsController::class, 'update']);
});

Route::middleware(['auth:sanctum'])->group(function(){
    
    Route::post('logout', [AuthController::class, 'logout']);

});