<?php

declare(strict_types=1);

namespace App\Application\FileTypeUseCase;

use App\Domain\FileType\Contracts\FileTypeContract;
use App\Http\Requests\FileType\FileTypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class FileTypeAllUseCase
{
    private $fileTypeContract;

    public function __construct(FileTypeContract $fileTypeContract)
    {
        $this->fileTypeContract = $fileTypeContract;
    }

    public function __invoke(FileTypeAllRequest $request): LengthAwarePaginator
    {
        return $this->fileTypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null);
    }
}
