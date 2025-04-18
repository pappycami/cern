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
        $tasks = $project->tasks()->latest()->get();
        return response()->json($tasks);
    }
}
