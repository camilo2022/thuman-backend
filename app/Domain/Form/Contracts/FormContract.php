<?php

declare(strict_types=1);

namespace App\Domain\Form\Contracts;

use App\Domain\Form\Form;
use App\Domain\Form\Values\FormId;
use App\Http\Requests\Form\FormAllRequest;
use App\Http\Requests\Form\FormFindRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface FormContract
{
    public function all(string|null $search, int|null $perPage, FormAllRequest $request): LengthAwarePaginator;

    public function find(FormFindRequest $request, FormId $formId): ?Form;

    public function store(Form $form): Form;

    public function update(Form $form, FormId $formId): Form;

    public function delete(FormId $formId): Form;

    public function restore(FormId $formId): Form;
}
