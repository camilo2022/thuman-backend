<?php

declare(strict_types=1);

namespace App\Application\FormationStatusUseCase;

use App\Domain\FormationStatus\Contracts\FormationStatusContract;
use App\Http\Requests\FormationStatus\FormationStatusAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class FormationStatusAllUseCase
{
    private $formationStatusContract;

    public function __construct(FormationStatusContract $formationStatusContract)
    {
        $this->formationStatusContract = $formationStatusContract;
    }

    public function __invoke(FormationStatusAllRequest $request): LengthAwarePaginator
    {
        return $this->formationStatusContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
