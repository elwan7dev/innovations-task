<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [NewPasswordController::class, 'forgotPassword'])
    ->middleware('guest')->name('password.email');

Route::post('/reset-password', [NewPasswordController::class, 'resetPassword'])
    ->middleware('guest')->name('password.update');

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    // CategoryController
    Route::apiResources([
        'categories' => CategoryController::class,
        'products' => ProductController::class,
        'users' => UserController::class,
    ]);
    Route::post('/products/import', [ProductController::class, 'import']);

    Route::post('users/block/{user}',[UserController::class,'block']);
    Route::post('users/unBlock/{user}',[UserController::class,'unBlock']);
    Route::get('/profile', function (){
        return auth()->user();
    });

    Route::post('/logout', [AuthController::class, 'logout']);

});

