<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns list of users",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Resource Not Found",
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getUsers()
    {
        $users = User::all();
        return response()->json($users);
    }
}
