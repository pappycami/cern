<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function getByTask(Task $task): JsonResponse
    {
        $project = $task->project()->latest()->paginate(10);
        return response()->json($project);
    }

    public function index(): JsonResponse
    {
        $project = Project::latest()->paginate(10);
        return response()->json($project);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project = Project::create($validated);
        return response()->json($project, 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($project);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);
        return response()->json($project);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'Projet supprimé']);
    }
}
