
<?php

    use App\Http\Controllers\API\ProjectController;

    Route::apiResource('projects', ProjectController::class);
    Route::apiResource('tasks', TaskController::class);
    Route::get('projects/{project}/tasks', [TaskController::class, 'getByProject']);

