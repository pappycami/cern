<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="CERN API",
 *     version="1.0.0",
 *     description="API pour la gestion des projets et tâches",
 *     @OA\Contact(
 *         email="contact@cern.test"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Serveur principal"
 * )
 */

abstract class Controller
{
    //
}
