<?php

declare(strict_types=1);

namespace App\Infrastructure\Controllers\Person;

use App\Application\PersonUseCase\PersonToggleUseCase;
use App\Domain\Person\Values\PersonId;
use App\Http\Requests\Person\PersonToggleRequest;
use App\Http\Resources\Person\PersonResource;
use App\Infrastructure\Repositories\PersonRepository;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

final class PersonToggleController
{
    use ApiResponser;
    use ApiMessage;
    private $personRepository;

    public function __construct(PersonRepository $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function __invoke(PersonToggleRequest $request, int $personId)
    {
        Gate::authorize('togglePerson', $personId);

        try {
            $personId = new PersonId($personId ?? null);
            $personToggleUseCase = new PersonToggleUseCase($this->personRepository);
            $person = new PersonResource($personToggleUseCase->__invoke($request, $personId));
            return $this->successResponse(
                $person,
                'La persona fue actualizado exitosamente.',
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
