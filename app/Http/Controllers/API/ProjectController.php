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

        $project = $task->project()
                        ->orderBy($sortBy, $sortOrder)
                        ->paginate(10);
        return response()->json($project);
    }

    public function index(): JsonResponse
    {
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        $filter  = request('filter');
        $keyword = request('keyword');

        $allowedOrder  = ['asc', 'desc'];
        $allowedSorts  = ['title','description', 'created_at', 'updated_at'];
        $allowedFilter = ['title', 'description'];

        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortOrder, $allowedOrder)) {
            $sortOrder = 'title';
        }

        if (!in_array($filter, $allowedFilter)) {
            $filter = 'title';
        }

        $query = Project::query();
        if($filter && $keyword) {
            $query->where($filter, "LIKE", "%".$keyword."%");
        }

        $project = $query->orderBy($sortBy, $sortOrder)->paginate(10);
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
        return response()->json($project, 203);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'Projet supprimé']);
    }
}
