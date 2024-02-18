<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\LogsLoginController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
 */
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);


    Route::get('loginLogs', [LogsLoginController::class, 'LogsLogin']);
    Route::get('loginLogsUser', [LogsLoginController::class, 'LogsLoginUser']);


    Route::get('listMovie', [MovieController::class, 'listMovie']);
    Route::get('searchMovie', [MovieController::class, 'SearchMovie']);
    Route::post('addFavoriteMovie', [MovieController::class, 'AddFavoriteMovie']);
    Route::get('listFavoriteMovie', [MovieController::class, 'ListFavoriteMovie']);

});

