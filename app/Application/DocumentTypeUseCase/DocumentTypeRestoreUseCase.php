<?php

declare(strict_types=1);

namespace App\Application\DocumentTypeUseCase;

use App\Domain\DocumentType\DocumentType;
use App\Domain\DocumentType\Contracts\DocumentTypeContract;
use App\Domain\DocumentType\Values\DocumentTypeId;

final class DocumentTypeRestoreUseCase
{
    private $documentTypeContract;

    public function __construct(DocumentTypeContract $documentTypeContract)
    {
        $this->documentTypeContract = $documentTypeContract;
    }

    public function __invoke(DocumentTypeId $documentTypeId): ?DocumentType
    {
        return $this->documentTypeContract->restore($documentTypeId);
    }
}
