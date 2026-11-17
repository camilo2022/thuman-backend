<?php

declare(strict_types=1);

namespace App\Application\FormTypeUseCase;

use App\Domain\FormType\Contracts\FormTypeContract;
use App\Http\Requests\FormType\FormTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class FormTypeAllUseCase
{
    private $formTypeContract;

    public function __construct(FormTypeContract $formTypeContract)
    {
        $this->formTypeContract = $formTypeContract;
    }

    public function __invoke(FormTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->formTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
