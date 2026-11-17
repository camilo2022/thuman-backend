<?php

namespace App\Http\Resources\Enterprise\Entity;

use App\Models\Entity;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class EntityCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'entities' => $this->collection->map(function ($entity) {
                return [
                    'id' => $entity->id,
                    'enterprise' => $entity->enterprise,
                    'entity_type' => $entity->entity_type,
                    'name' => $entity->name,
                    'country' => $entity->country,
                    'document_type' => $entity->document_type,
                    'document_number' => $entity->document_number,
                    'person' => $entity->person,
                    'about_us' => $entity->about_us,
                    'mission' => $entity->mission,
                    'vision' => $entity->vision,
                    'images' => $entity->images,
                    'created_at' => $this->formatDate($entity->created_at),
                    'updated_at' => $this->formatDate($entity->updated_at),
                    'deleted_at' => $this->formatDate($entity->deleted_at)
                ];
            }),
            'filters' => $this->generateFilters(Entity::get()),
            'meta' => [
                'pagination' => $this->paginationMeta(),
            ],
        ];
    }

    protected function formatDate($date)
    {
        return $date ? Carbon::parse($date)->format('Y-m-d H:i:s') : $date;
    }

    protected function paginationMeta()
    {
        return [
            'total' => $this->total(),
            'count' => $this->count(),
            'per_page' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }

    protected function generateFilters($entities)
    {
        $enterprises = $entities
            ->groupBy(fn($v) => $v->enterprise->name ?? 'Sin empresa')
            ->map(fn($group) => $group->count())
            ->toArray();

        $countries = [];

        foreach ($entities as $entity) {
            $country = $entity->country;

            if (!$country) {
                continue;
            }

            $name = is_string($country) ? $country : ($country->name ?? null);

            if (!$name) {
                continue;
            }

            if (!isset($countries[$name])) {
                $countries[$name] = [
                    'label' => $name,
                    'count' => 0,
                ];
            }

            $countries[$name]['count']++;
        }

        return [
            'enterprise' => ['label' => 'Empresa', 'options' => collect($enterprises)->map(fn($v, $k) => ['label' => $k, 'count' => $v])->values()],
            'ubication' => ['label' => 'Ubicación', 'options' => collect($countries)->values()],
        ];
    }
}
