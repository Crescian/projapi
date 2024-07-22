<?php

use Illuminate\Http\Request;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post("register", [App\Http\Controllers\UserController::class, 'register']);
// Route::post("login", [App\Http\Controllers\UserController::class, 'login']);

// Route::group([], function () {
//     Route::post('profile', [UserController::class, 'profile']);
// });

// route::get('company', [App\Http\Controllers\CompanyController::class, 'index']);
// route::get('user', [App\Http\Controllers\UserController::class, 'index']);

Route::post("register", [App\Http\Controllers\UserController::class, 'register']);
Route::post("login", [App\Http\Controllers\UserController::class, 'login']);
Route::get('company', [App\Http\Controllers\CompanyController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('getUserDetails', [App\Http\Controllers\UserController::class, 'getUserDetails']);
});

// Individual protected route (example, if you still want it separately)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
