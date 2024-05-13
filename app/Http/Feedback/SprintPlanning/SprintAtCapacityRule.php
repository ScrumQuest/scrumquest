<?php

namespace App\Http\Feedback\SprintPlanning;

use App\Http\Feedback\FeedbackRule;
use App\Models\Sprint;
use Illuminate\Database\Eloquent\Collection;

class SprintAtCapacityRule implements FeedbackRule
{
    function __construct(Sprint $sprint) {
        $this->sprint = $sprint;
    }

    public function run(): array
    {
        $maxPlanningValue = $this->sprint->getMaxPlanningValue();
        $lastPlannedItemPerAssignee = $this->calculateLastPlannedItemPerAssignee($this->sprint->backlogItems);

        $capacityFactors = [];
        foreach ($lastPlannedItemPerAssignee as $planningValue) {
            $capacityFactors[] = $planningValue / $maxPlanningValue;
        }

        if(count($capacityFactors) == 0) {
            return [];
        }
        
        $capacityFactor = array_sum($capacityFactors) / count($capacityFactors);

        if($capacityFactor < 0.75) {
            return ["SP-01" => "Your sprint is less than 75% full. Consider adding more backlog items to the sprint."];
        } else if($capacityFactor > 0.85) {
            return ["SP-01" => "Your sprint is more than 85% full. This might not leave enough room for unforeseen circumstances during the sprint. Consider removing a bit of work from the sprint."];
        }
        return [];
    }

    private function calculateLastPlannedItemPerAssignee(Collection $backlogItems): array {
        // We find the latest planned item for each assignee by calculating ((week_in_sprint - 1) * 5) + day_in_sprint
        // We store this value per assignee in an associative array
        $lastPlannedItemPerAssignee = [];

        foreach ($backlogItems as $backlogItem) {
            $assignee = $backlogItem->assignee_id;
            $planningValue = $backlogItem->getPlanningValue();
            if(array_key_exists($assignee, $lastPlannedItemPerAssignee)) {
                if($planningValue > $lastPlannedItemPerAssignee[$assignee]) {
                    $lastPlannedItemPerAssignee[$assignee] = $planningValue;
                }
            } else {
                $lastPlannedItemPerAssignee[$assignee] = $planningValue;
            }
        }
        return $lastPlannedItemPerAssignee;
    }
}
