<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskCreateRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        $status = request('status');

        $allowedOrder = ['asc', 'desc'];
        $allowedSorts = ['title', 'created_at', 'updated_at', 'status'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortOrder, $allowedOrder)) {
            $sortOrder = 'desc';
        }

        $query = Task::with('project');
        if($status) {
            $query->where('status', $status);
        }

        $task = $query->latest()->paginate(10);
        return response()->json($task);
    }

    public function store(TaskCreateRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());
        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json($task->load('project'));
    }

    public function update(TaskUpdateRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());
        return response()->json($task);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json(['message' => 'Tâche supprimée']);
    }

    public function getByProject(Project $project): JsonResponse
    {
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        $status = request('status');

        $allowedOrder = ['asc', 'desc'];
        $allowedSorts = ['title', 'created_at', 'updated_at', 'status'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortOrder, $allowedOrder)) {
            $sortOrder = 'desc';
        }

        $query = $project->tasks();
        if($status) {
            $query->where('status', $status);
        }

        $tasks =$query->orderBy($sortBy, $sortOrder)->paginate(10);
        return response()->json($tasks);
    }
}
