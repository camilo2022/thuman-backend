<?php

declare(strict_types=1);

namespace App\Domain\Person\Values;

final class PersonDocumentIssueDate
{
    private $value;
    
    public function __construct(string|null $documentIssueDate)
    {
        $this->value = $documentIssueDate;
    }

    public function value(): string|null
    {
        return $this->value;
    }
}