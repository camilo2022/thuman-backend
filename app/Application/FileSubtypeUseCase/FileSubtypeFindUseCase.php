<?php

declare(strict_types=1);

namespace App\Application\FileSubtypeUseCase;

use App\Domain\FileSubtype\FileSubtype;
use App\Domain\FileSubtype\Contracts\FileSubtypeContract;
use App\Domain\FileSubtype\Values\FileSubtypeId;

final class FileSubtypeFindUseCase
{
    private $fileSubtypeContract;

    public function __construct(FileSubtypeContract $fileSubtypeContract)
    {
        $this->fileSubtypeContract = $fileSubtypeContract;
    }

    public function __invoke(FileSubtypeId $fileSubtypeId): ?FileSubtype
    {
        return $this->fileSubtypeContract->find($fileSubtypeId);
    }
}
