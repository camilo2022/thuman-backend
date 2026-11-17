<?php

declare(strict_types=1);

namespace App\Application\FormUseCase;

use App\Domain\Form\Contracts\FormContract;
use App\Http\Requests\Form\FormAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class FormAllUseCase
{
    private $formContract;

    public function __construct(FormContract $formContract)
    {
        $this->formContract = $formContract;
    }

    public function __invoke(FormAllRequest $request): LengthAwarePaginator
    {
        return $this->formContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
