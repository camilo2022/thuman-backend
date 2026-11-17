<?php

namespace App\Http\Resources\Vacant\Application;

use App\Models\Application;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ApplicationCollection extends ResourceCollection
{
    protected $vacantId;

    public function __construct($resource, $vacantId = null)
    {
        parent::__construct($resource);
        $this->vacantId = is_object($vacantId)
            ? $vacantId->value()
            : $vacantId;
    }

    public function toArray(Request $request): array
    {
        return [
            'applications' => $this->collection->map(function ($application) {
                return [
                    'id' => $application->id,
                    'person' => $application->person,
                    'vacant' => $application->vacant,
                    'form' => $application->form,
                    'questions' => $application->questions,
                    'answers' => $application->answers,
                    'salary' => $application->salary,
                    'observation' => $application->observation,
                    'status' => $application->status,
                    'compatibility' => $this->generateCompatibility($application),
                    'interview_initial' => $application->interview_initial,
                    'interview_psychotechnical' => $application->interview_psychotechnical,
                    'test_technical' => $application->test_technical,
                    'test_psychotechnical' => $application->test_psychotechnical,
                    'exam_medical' => $application->exam_medical,
                    'exam_polygraph' => $application->exam_polygraph,
                    'contract' => $application->contract,
                    'created_at' => $this->formatDate($application->created_at),
                    'updated_at' => $this->formatDate($application->updated_at)
                ];
            }),
            'filters' => $this->generateFilters(),
            'statuses' => Application::statuses(),
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

    protected function generateFilters()
    {
        $baseQuery = Application::when($this->vacantId, function ($query) {
                $query->where('vacant_id', $this->vacantId);
            });

        return [
            Application::PENDING => (clone $baseQuery)->where('status', Application::PENDING)->count(),
            Application::REVIEW => (clone $baseQuery)->where('status', Application::REVIEW)->count(),
            Application::TEST => (clone $baseQuery)->where('status', Application::TEST)->count(),
            Application::FINALIST => (clone $baseQuery)->where('status', Application::FINALIST)->count(),
            Application::HIRING => (clone $baseQuery)->where('status', Application::HIRING)->count(),
            Application::REJECTED => (clone $baseQuery)->where('status', Application::REJECTED)->count(),
            Application::WITHDRAWN => (clone $baseQuery)->where('status', Application::WITHDRAWN)->count(),
            Application::LINKED => (clone $baseQuery)->where('status', Application::LINKED)->count(),
        ];
    }

    protected function generateCompatibility($application)
    {
        $personSkills = $application->person->profile->professional_skills ?? [];
        $jobSkills = $application->vacant->position->required_skills ?? [];

        $personSkillsById = collect($personSkills)->keyBy(fn($s) => $s['skill_id']);
        $jobSkillsById = collect($jobSkills)->keyBy(fn($s) => $s['skill_id']);

        $totalCompatibility = 0;
        $skillsEvaluated = 0;

        foreach ($jobSkillsById as $skillId => $jobSkill) {
            $jobLevel = floatval($jobSkill['percentage']);
            $personLevel = isset($personSkillsById[$skillId])
                ? floatval($personSkillsById[$skillId]['percentage'] ?? $personSkillsById[$skillId]['value'] ?? 0)
                : null;

            if (is_null($personLevel)) {
                $compatibility = 0;
            } else {
                $compatibility = ($personLevel >= $jobLevel) ? 100 : (($personLevel / $jobLevel) * 100);
            }

            $totalCompatibility += $compatibility;
            $skillsEvaluated++;
        }

        if ($skillsEvaluated === 0) {
            return 100;
        }

        return round($totalCompatibility / $skillsEvaluated, 2);
    }
}
