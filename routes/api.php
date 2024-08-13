<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;

Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);

Route::group([
    "middleware" => ["auth:api"]
], function(){
    Route::get("profile", [ApiController::class, "profile"]);
    Route::get("logout", [ApiController::class, "logout"]);

    Route::apiResource('employees', EmployeeController::class);
});
