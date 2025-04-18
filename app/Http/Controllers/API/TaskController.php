<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(): JsonResponse
    {
        $task = Task::with('project')->latest()->paginate(10);
        return response()->json($task);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'status'     => 'in:pending,in_progress,done',
            'project_id' => 'required|exists:projects,id',
        ]);
        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    public function show(Task $task): JsonResponse
    {
        return response()->json($task->load('project'));
    }

    public function update(Request $request, Task $task): JsonResponse
    {
        $validated = $request->validate([
            'title'      => 'required|string|max:255',
            'status'     => 'in:pending,in_progress,done',
            'project_id' => 'required|exists:projects,id',
        ]);

        $task->update($validated);
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

        $allowedOrder = ['asc', 'desc'];
        $allowedSorts = ['title', 'created_at', 'updated_at', 'status'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortOrder, $allowedOrder)) {
            $sortOrder = 'desc';
        }

        $tasks = $project->tasks()
                         ->orderBy($sortBy, $sortOrder)
                         ->paginate(10);
        return response()->json($tasks);
    }
}
