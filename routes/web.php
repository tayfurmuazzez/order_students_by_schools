<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SchoolController;
use App\Http\Controllers\Admin\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get("/",function(){ echo "Welcome MgsSoft Task";});

Route::group(['middleware' => ['auth:sanctum']],function(){
    Route::prefix('admin')->middleware(['role:super_admin'])->group(function(){
        Route::prefix('school')->group(function (){
            Route::post('create',[SchoolController::class,'create'])->name('createSchool');
            Route::get('get',[SchoolController::class,'get'])->name('getSchoolList');
            Route::get('read/{id}',[SchoolController::class,'read'])->name('getOneSchool');
            Route::post('update',[SchoolController::class,'update'])->name('updateSchool');
            Route::post('delete',[SchoolController::class,'delete'])->name('deleteSchool');
        });

        Route::prefix('student')->group(function (){
            Route::post('create',[StudentController::class,'create'])->name('createStudent');
            Route::get('get',[StudentController::class,'get'])->name('getStudentList');
            Route::get('read/{id}',[StudentController::class,'read'])->name('getOneStudent');
            Route::post('update',[StudentController::class,'update'])->name('updateStudent');
            Route::post('delete',[StudentController::class,'delete'])->name('deleteStudent');
        });
    });
});
