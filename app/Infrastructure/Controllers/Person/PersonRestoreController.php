<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonRestoreUseCase;
use App\Domain\Person\Values\PersonId;
use App\Http\Resources\Person\PersonResource;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonRestoreController
{
    use ApiResponser;
    use ApiMessage;
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function __invoke(int $personId)
    {
        Gate::authorize('restorePerson', $personId);

        try {
            $personId = new PersonId($personId);
            $personRestoreUseCase = new PersonRestoreUseCase($this->personRepository);
            $person = new PersonResource($personRestoreUseCase->__invoke($personId));
            return $this->successResponse(
                $person,
                'La persona fue activada exitosamente.',
                204
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
