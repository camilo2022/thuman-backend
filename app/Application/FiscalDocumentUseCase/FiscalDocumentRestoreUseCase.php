<?php

declare(strict_types=1);

namespace App\Application\FiscalDocumentUseCase;

use App\Domain\FiscalDocument\FiscalDocument;
use App\Domain\FiscalDocument\Contracts\FiscalDocumentContract;
use App\Domain\FiscalDocument\Values\FiscalDocumentId;

final class FiscalDocumentRestoreUseCase
{
    private $fiscalDocumentContract;

    public function __construct(FiscalDocumentContract $fiscalDocumentContract)
    {
        $this->fiscalDocumentContract = $fiscalDocumentContract;
    }

    public function __invoke(FiscalDocumentId $fiscalDocumentId): ?FiscalDocument
    {
        return $this->fiscalDocumentContract->restore($fiscalDocumentId);
    }
}
