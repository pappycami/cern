
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TaskController;

Route::apiResource('projects', ProjectController::class);
Route::get('projects/{project}/tasks', [TaskController::class, 'getByProject']);

Route::apiResource('tasks', TaskController::class);
Route::get('tasks/{task}/project', [ProjectController::class, 'getByTask']);
