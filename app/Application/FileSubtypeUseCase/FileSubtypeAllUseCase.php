<?php

declare(strict_types=1);

namespace App\Application\FileSubtypeUseCase;

use App\Domain\FileSubtype\Contracts\FileSubtypeContract;
use App\Http\Requests\FileSubtype\FileSubtypeAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

final class FileSubtypeAllUseCase
{
    private $fileSubtypeContract;

    public function __construct(FileSubtypeContract $fileSubtypeContract)
    {
        $this->fileSubtypeContract = $fileSubtypeContract;
    }

    public function __invoke(FileSubtypeAllRequest $request): LengthAwarePaginator
    {
        return $this->fileSubtypeContract->all($request->input('search'), is_numeric($request->input('perPage')) ? $request->integer('perPage') : null, $request);
    }
}
