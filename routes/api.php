<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Api\StudentController;

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

Route::post('/signin',[AuthController::class,'signin'])->middleware("throttle:10,1");

Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::prefix('student')->group(function (){
        Route::post('create',[StudentController::class,'create'])->name('createStudent');
        Route::get('get',[StudentController::class,'get'])->name('getStudentList');
        Route::get('read/{id}',[StudentController::class,'read'])->name('getOneStudent');
        Route::post('update',[StudentController::class,'update'])->name('updateStudent');
        Route::post('delete',[StudentController::class,'delete'])->name('deleteStudent');
    });
});

