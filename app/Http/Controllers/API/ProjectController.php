<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectCreateRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Projects",
 *     description="Gestion des projets"
 * )
 */
class ProjectController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/projects/by-task/{task}",
     *     summary="Afficher un projet lié à une tâche",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *         name="task",
     *         in="path",
     *         required=true,
     *         description="ID de la tâche",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Projet lié à la tâche")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/projects",
     *     summary="Lister les projets avec tri et filtre",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *         name="sort_by",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="sort_order",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string", enum={"asc", "desc"})
     *     ),
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="keyword",
     *         in="query",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Liste des projets")
     * )
     */
    public function index(): JsonResponse
    {
        $sortBy = request('sort_by', 'created_at');
        $sortOrder = request('sort_order', 'desc');
        $filter  = request('filter');
        $keyword = request('keyword');

        $allowedOrder  = ['asc', 'desc'];
        $allowedSorts  = ['title', 'description', 'created_at', 'updated_at'];
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
        if ($filter && $keyword) {
            $query->where($filter, "LIKE", "%" . $keyword . "%");
        }

        if ($sortBy && $sortOrder) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $project = $query->paginate(10);
        return response()->json($project);
    }

    /**
     * @OA\Post(
     *     path="/api/projects",
     *     summary="Créer un nouveau projet",
     *     tags={"Projects"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProjectCreateRequest")
     *     ),
     *     @OA\Response(response=201, description="Projet créé")
     * )
     */
    public function store(ProjectCreateRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());
        return response()->json($project, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/projects/{project}",
     *     summary="Afficher un projet spécifique",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Détails du projet")
     * )
     */
    public function show(Project $project): JsonResponse
    {
        return response()->json($project);
    }

    /**
     * @OA\Put(
     *     path="/api/projects/{project}",
     *     summary="Mettre à jour un projet",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ProjectUpdateRequest")
     *     ),
     *     @OA\Response(response=203, description="Projet mis à jour")
     * )
     */
    public function update(ProjectUpdateRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());
        return response()->json($project, 203);
    }

    /**
     * @OA\Delete(
     *     path="/api/projects/{project}",
     *     summary="Supprimer un projet",
     *     tags={"Projects"},
     *     @OA\Parameter(
     *         name="project",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Projet supprimé")
     * )
     */
    public function destroy(Project $project): JsonResponse
    {
        $project->delete();
        return response()->json(['message' => 'Projet supprimé']);
    }
}
