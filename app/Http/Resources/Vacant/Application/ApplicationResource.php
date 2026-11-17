<?php

namespace App\Http\Resources\Vacant\Application;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'application' => [
                'id' => $this->id()->value(),
                'person_id' => $this->personId()->value(),
                'vacant_id' => $this->vacantId()->value(),
                'form_id' => $this->formId()->value(),
                'questions' => $this->questions()->value(),
                'answers' => $this->answers()->value(),
                'salary' => $this->salary()->value(),
                'observation' => $this->observation()->value(),
                'status' => $this->status()->value(),
                'compatibility' => $this->generateCompatibility(),
                'created_at' => $this->createdAt()->value(),
                'updated_at' => $this->updatedAt()->value(),
                'relationships' => $this->relationships()->toArray()
            ],
        ];
    }

    protected function generateCompatibility()
    {
        $relationships = $this->relationships()->toArray();

        $personSkills = $relationships['person']['profile']['professional_skills'] ?? [];
        $jobSkills = $relationships['vacant']['position']['required_skills'] ?? [];

        $personSkillsById = collect($personSkills)->keyBy(fn($s) => $s['skill_id']);
        $jobSkillsById = collect($jobSkills)->keyBy(fn($s) => $s['skill_id']);

        $totalCompatibility = 0;
        $skillsEvaluated = 0;

        foreach ($jobSkillsById as $skillId => $jobSkill) {
            $jobLevel = floatval($jobSkill['percentage']);

            if ($jobLevel <= 0) {
                continue;
            }

            $personLevel = isset($personSkillsById[$skillId])
                ? floatval($personSkillsById[$skillId]['percentage'] ?? $personSkillsById[$skillId]['value'] ?? 0)
                : null;

            $compatibility = is_null($personLevel)
                ? 0
                : (($personLevel >= $jobLevel) ? 100 : (($personLevel / $jobLevel) * 100));

            $totalCompatibility += $compatibility;
            $skillsEvaluated++;
        }

        return $skillsEvaluated === 0
            ? 100
            : round($totalCompatibility / $skillsEvaluated, 2);
    }
}
