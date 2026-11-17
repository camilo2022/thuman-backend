<?php

declare(strict_types=1);

namespace App\Application\FileTypeUseCase;

use App\Domain\FileType\FileType;
use App\Domain\FileType\Contracts\FileTypeContract;
use App\Domain\FileType\Values\FileTypeId;

final class FileTypeFindUseCase
{
    private $fileTypeContract;

    public function __construct(FileTypeContract $fileTypeContract)
    {
        $this->fileTypeContract = $fileTypeContract;
    }

    public function __invoke(FileTypeId $fileTypeId): ?FileType
    {
        return $this->fileTypeContract->find($fileTypeId);
    }
}
