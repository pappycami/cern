
<?php

use App\Http\Controllers\API\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\UserController;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/users', [UserController::class, 'getUsers']);

    Route::apiResource('projects', ProjectController::class);
    Route::get('projects/{project}/tasks', [TaskController::class, 'getByProject']);

    Route::apiResource('tasks', TaskController::class);
    Route::get('tasks/{task}/project', [ProjectController::class, 'getByTask']);
});
