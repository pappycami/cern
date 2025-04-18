
<?php

    use App\Http\Controllers\API\ProjectController;
    use App\Http\Controllers\API\TaskController;
    use Illuminate\Routing\Route;

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::get('projects/{project}/tasks', [TaskController::class, 'getByProject']);

