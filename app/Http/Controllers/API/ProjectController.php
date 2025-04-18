<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;

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

    public function store(ProjectCreateRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());
        return response()->json($project, 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json($project);
    }

    public function update(ProjectUpdateRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());
        return response()->json($project, 203);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'Projet supprimé']);
    }
}
