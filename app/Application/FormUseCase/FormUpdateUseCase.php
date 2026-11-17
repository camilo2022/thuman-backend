<?php

declare(strict_types=1);

namespace App\Application\FormUseCase;

use App\Domain\Form\Contracts\FormContract;
use App\Domain\Form\Form;
use App\Domain\Form\Values\FormAssignableId;
use App\Domain\Form\Values\FormAssignableType;
use App\Domain\Form\Values\FormEntityId;
use App\Domain\Form\Values\FormFormTypeId;
use App\Domain\Form\Values\FormId;
use App\Domain\Form\Values\FormModelId;
use App\Domain\Form\Values\FormModelType;
use App\Domain\Form\Values\FormPositionId;
use App\Domain\Form\Values\FormQuestions;
use App\Http\Requests\Form\FormUpdateRequest;

final class FormUpdateUseCase
{
    private $formContract;

    public function __construct(FormContract $formContract)
    {
        $this->formContract = $formContract;
    }

    public function __invoke(FormUpdateRequest $request, FormId $formId): Form
    {
        return $this->formContract->update(
            new Form(
                new FormId($formId->value()),
                new FormFormTypeId($request->input('form_type_id')),
                new FormAssignableId($request->input('assignable_id')),
                new FormAssignableType($request->input('assignable_type')),
                new FormModelId($request->input('model_id')),
                new FormModelType($request->input('model_type')),
                new FormQuestions($request->input('questions'))
            ),
            $formId
        );
    }
}
