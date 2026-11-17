<?php

declare(strict_types=1);

namespace App\Domain\Enterprise\Entity\Gallery\Contracts;

use App\Domain\Enterprise\Entity\Gallery\Gallery;
use App\Domain\Enterprise\Entity\Gallery\Values\GalleryId;
use App\Domain\Enterprise\Entity\Values\EntityId;
use App\Http\Requests\Enterprise\Entity\Gallery\GalleryAllRequest;
use Illuminate\Pagination\LengthAwarePaginator;

interface GalleryContract
{
    public function all(string|null $search, int|null $perPage, EntityId $entityId, GalleryAllRequest $request): LengthAwarePaginator;

    public function find(GalleryId $galleryId): ?Gallery;

    public function store(Gallery $gallery): Gallery;

    public function delete(GalleryId $galleryId): Gallery;

    public function destroy(GalleryId $galleryId): void;

    public function restore(GalleryId $galleryId): Gallery;
}
