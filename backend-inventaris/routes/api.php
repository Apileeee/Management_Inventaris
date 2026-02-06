<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AuthController;

Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:api')->group(function(){
    Route::apiResource('members', MemberController::class);
    Route::apiResource('inventories', InventoryController::class);
});
