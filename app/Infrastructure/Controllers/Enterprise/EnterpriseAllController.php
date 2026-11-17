<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Enterprise;

use App\Application\EnterpriseUseCase\EnterpriseAllUseCase;
use App\Http\Requests\Enterprise\EnterpriseAllRequest;
use App\Http\Resources\Enterprise\EnterpriseCollection;
use App\Infrastructure\Repositories\EnterpriseRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class EnterpriseAllController
{
    use ApiResponser;
    use ApiMessage;
    private $enterpriseRepository;

    public function __construct(EnterpriseRepository $enterpriseRepository)
    {
        $this->enterpriseRepository = $enterpriseRepository;
    }

    /**
     * @OA\Get(
     *     path="/Enterprises/All",
     *     summary="Listar todas las empresas",
     *     tags={"Enterprises"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Término de búsqueda para filtrar empresas",
     *         required=false,
     *         @OA\Schema(type="string", maxLength=100, example="software")
     *     ),
     *     @OA\Parameter(
     *         name="perPage",
     *         in="query",
     *         description="Número de registros por página",
     *         required=false,
     *         @OA\Schema(type="integer", format="int32", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de empresas obtenido exitosamente.",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="enterprises", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="Acme Corp"),
     *                         @OA\Property(property="description", type="string", example="Empresa dedicada al desarrollo de software."),
     *                         @OA\Property(property="entities", type="array",
     *                             @OA\Items(
     *                                 @OA\Property(property="id", type="integer", example=10),
     *                                 @OA\Property(property="enterprise_id", type="integer", example=1),
     *                                 @OA\Property(property="entity_type_id", type="integer", example=2),
     *                                 @OA\Property(property="name", type="string", example="Acme Colombia S.A.S."),
     *                                 @OA\Property(property="country_id", type="integer", example=170),
     *                                 @OA\Property(property="document_type_id", type="integer", example=5),
     *                                 @OA\Property(property="document_number", type="string", example="901234567-8"),
     *                                 @OA\Property(property="person_id", type="integer", example=25),
     *                                 @OA\Property(property="about_us", type="string", nullable=true, example="Somos líderes en soluciones cloud."),
     *                                 @OA\Property(property="mission", type="string", nullable=true, example="Impulsar la transformación digital."),
     *                                 @OA\Property(property="vision", type="string", nullable=true, example="Ser referentes en LATAM."),
     *                                 @OA\Property(property="headquarters", type="array",
     *                                     @OA\Items(
     *                                         @OA\Property(property="id", type="integer", example=100),
     *                                         @OA\Property(property="entity_id", type="integer", example=10),
     *                                         @OA\Property(property="name", type="string", example="Sede Principal"),
     *                                         @OA\Property(property="city_id", type="integer", example=76001),
     *                                         @OA\Property(property="address", type="string", example="Calle 123 #45-67"),
     *                                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00")
     *                                     )
     *                                 )
     *                             )
     *                         ),
     *                         @OA\Property(property="logo", type="object",
     *                             @OA\Property(property="id", type="integer", example=501),
     *                             @OA\Property(property="model_type", type="string", example="App\\Models\\Enterprise"),
     *                             @OA\Property(property="model_id", type="integer", example=1),
     *                             @OA\Property(property="name", type="string", example="logo_acme.png"),
     *                             @OA\Property(property="file_type_id", type="integer", example=11),
     *                             @OA\Property(property="file_subtype_id", type="integer", example=1101),
     *                             @OA\Property(property="path", type="string", example="storage/enterprises/1/logo_acme.png"),
     *                             @OA\Property(property="mime", type="string", example="image/png"),
     *                             @OA\Property(property="extension", type="string", example="png"),
     *                             @OA\Property(property="size", type="number", format="float", example=123.45),
     *                             @OA\Property(property="metadata", type="object", example={"width":512,"height":512}),
     *                             @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                             @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00"),
     *                             @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                         ),
     *                         @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-02 20:00:00"),
     *                         @OA\Property(property="updated_at", type="string", format="date-time", example="2025-08-10 10:00:00"),
     *                         @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example=null)
     *                     )
     *                 ),
     *                 @OA\Property(property="meta", type="object",
     *                     @OA\Property(property="pagination", type="object",
     *                         @OA\Property(property="total", type="integer", example=50),
     *                         @OA\Property(property="count", type="integer", example=10),
     *                         @OA\Property(property="per_page", type="integer", example=10),
     *                         @OA\Property(property="current_page", type="integer", example=1),
     *                         @OA\Property(property="total_pages", type="integer", example=5)
     *                     )
     *                 )
     *             ),
     *             @OA\Property(property="message", type="string", example="Las empresas fueron consultadas exitosamente."),
     *             @OA\Property(property="error", type="boolean", example=false),
     *             @OA\Property(property="status", type="integer", example=200)
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación.",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error de validación."),
     *             @OA\Property(
     *                 property="attributes",
     *                 type="object",
     *                 @OA\Property(property="search", type="string", example="Término de búsqueda"),
     *                 @OA\Property(property="perPage", type="string", example="Número de registros por página")
     *             ),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="search",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser una cadena de caracteres.")
     *                 ),
     *                 @OA\Property(
     *                     property="perPage",
     *                     type="array",
     *                     @OA\Items(type="string", example="Debe ser un valor numérico.")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error inesperado al obtener las empresas.",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Error al obtener las empresas: Error en la base de datos.")
     *         )
     *     )
     * )
     */
    public function __invoke(EnterpriseAllRequest $request)
    {
        Gate::authorize('allEnterprise');
        
        try {
            $enterpriseAllUseCase = new EnterpriseAllUseCase($this->enterpriseRepository);
            $enterprises = new EnterpriseCollection($enterpriseAllUseCase->__invoke($request));
            return $this->successResponse(
                $enterprises,
                'Las empresas fueron consultadas exitosamente.',
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
