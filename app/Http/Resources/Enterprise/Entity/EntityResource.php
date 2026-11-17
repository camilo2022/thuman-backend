<?php

namespace App\Http\Resources\Enterprise\Entity;

use App\Models\Entity;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'entity' => [
                'id' => $this->id()->value(),
                'enterprise_id' => $this->enterpriseId()->value(),
                'entity_type_id' => $this->entityTypeId()->value(),
                'name' => $this->name()->value(),
                'country_id' => $this->countryId()->value(),
                'document_type_id' => $this->documentTypeId()->value(),
                'document_number' => $this->documentNumber()->value(),
                'person_id' => $this->personId()->value(),
                'about_us' => $this->aboutUs()->value(),
                'mission' => $this->mission()->value(),
                'vision' => $this->vision()->value(),
                'settings' => [
                    'vacations' => $this->settings()->vacations()
                ],
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'deleted_at' => $this->deletedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
            'filters' => $this->generateFilters(Entity::where('id', $this->id()->value())->get()),
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
