<?php

declare(strict_types=1);

namespace App\Application\DocumentTypeUseCase;

use App\Domain\DocumentType\Contracts\DocumentTypeContract;
use App\Domain\DocumentType\DocumentType;
use App\Domain\DocumentType\Values\DocumentTypeDescription;
use App\Domain\DocumentType\Values\DocumentTypeId;
use App\Domain\DocumentType\Values\DocumentTypeItemId;
use App\Domain\DocumentType\Values\DocumentTypeName;
use App\Domain\DocumentType\Values\DocumentTypePersonTypeId;
use App\Domain\DocumentType\Values\DocumentTypeSettings;
use App\Http\Requests\DocumentType\DocumentTypeSettingRequest;

final class DocumentTypeSettingUseCase
{
    private $documentTypeContract;

    public function __construct(DocumentTypeContract $documentTypeContract)
    {
        $this->documentTypeContract = $documentTypeContract;
    }

    public function __invoke(DocumentTypeSettingRequest $request, DocumentTypeId $documentTypeId): DocumentType
    {
        return $this->documentTypeContract->update(
            new DocumentType(
                new DocumentTypeId($documentTypeId->value()),
                new DocumentTypePersonTypeId($request->integer('person_type_id')),
                new DocumentTypeItemId($request->integer('item_id')),
                new DocumentTypeName($request->input('name')),
                new DocumentTypeDescription($request->input('description')),
                new DocumentTypeSettings((object) $request->input('settings'))
            ),
            $documentTypeId
        );
    }
}
