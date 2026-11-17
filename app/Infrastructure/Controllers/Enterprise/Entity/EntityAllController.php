<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise\Entity;

use App\Application\EnterpriseUseCase\EntityUseCase\EntityAllUseCase;
use App\Domain\Enterprise\Values\EnterpriseId;
use App\Http\Requests\Enterprise\Entity\EntityAllRequest;
use App\Http\Resources\Enterprise\Entity\EntityCollection;
use App\Infrastructure\Repositories\EntityRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EntityAllController
{
    use ApiResponser;
    use ApiMessage;
    private $entityRepository;

    public function __construct(EntityRepository $entityRepository)
    {
        $this->entityRepository = $entityRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/Entities/All/{enterpriseId}",
     *     summary="Listar todas las entidades de una empresa",
     *     tags={"Enterprises - Entities"},
     *     @OA\Parameter(
     *         name="enterpriseId",
     *         in="path",
     *         required=true,
     *         description="ID de la empresa",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         required=false,
     *         description="Término de búsqueda para filtrar entidades",
     *         @OA\Schema(type="string", example="Tecnología")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         required=false,
     *         description="Número de registros por página",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Las entidades fueron consultadas exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="entities", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="enterprise", type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Enterprise XYZ")
     *                     ),
     *                     @OA\Property(property="company_type", type="string", example="S.A.S"),
     *                     @OA\Property(property="name", type="string", example="Entidad de Tecnología"),
     *                     @OA\Property(property="country", type="object",
     *                         @OA\Property(property="id", type="integer", example=57),
     *                         @OA\Property(property="name", type="string", example="Colombia")
     *                     ),
     *                     @OA\Property(property="document_type", type="object",
     *                         @OA\Property(property="id", type="integer", example=2),
     *                         @OA\Property(property="name", type="string", example="NIT")
     *                     ),
     *                     @OA\Property(property="document_number", type="string", example="901234567-8"),
     *                     @OA\Property(property="person", type="object",
     *                         @OA\Property(property="id", type="integer", example=3),
     *                         @OA\Property(property="first_name", type="string", example="Carlos"),
     *                         @OA\Property(property="first_last_name", type="string", example="Gómez"),
     *                         @OA\Property(property="email", type="string", example="carlos.gomez@example.com"),
     *                         @OA\Property(property="phone_number", type="string", example="+57 3216549870")
     *                     ),
     *                     @OA\Property(property="about_us", type="string", example="Somos una entidad dedicada a la innovación."),
     *                     @OA\Property(property="mission", type="string", example="Ofrecer soluciones tecnológicas de calidad."),
     *                     @OA\Property(property="vision", type="string", example="Ser líderes en transformación digital en Latinoamérica."),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-18 10:00:00"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-18 12:00:00"),
     *                     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                 )
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="pagination", type="object",
     *                     @OA\Property(property="total", type="integer", example=35),
     *                     @OA\Property(property="count", type="integer", example=10),
     *                     @OA\Property(property="per_page", type="integer", example=10),
     *                     @OA\Property(property="current_page", type="integer", example=1),
     *                     @OA\Property(property="total_pages", type="integer", example=4)
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las entidades consultadas exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al obtener las entidades",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener las entidades: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(EntityAllRequest $request, int|null $enterpriseId = null)
    {
        Gate::authorize('allEntity');
        
        try {
            $enterpriseId = new EnterpriseId($enterpriseId ?? null);
            $entityAllUseCase = new EntityAllUseCase($this->entityRepository);
            $entitiess = new EntityCollection($entityAllUseCase->__invoke($request, $enterpriseId));
            return $this->successResponse(
                $entitiess,
                'Las entidades fueron consultadas exitosamente.',
                200
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
