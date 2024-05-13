<?php

namespace App\Http\Feedback\SprintPlanning;

use App\Models\Sprint;

class EstimationSizeRule implements \App\Http\Feedback\FeedbackRule
{
    private int $maxPlannedDays = 3;
    private Sprint $sprint;

    function __construct(Sprint $sprint) {
        $this->sprint = $sprint;
    }

    /**
     * @inheritDoc
     */
    public function run(): array
    {
        // For every user, check if the planned estimate is more than 'maxPlannedDays'.
        // This is done by creating an array per user with the days that they have items planned. If the difference
        // between two planned items is bigger than 'maxPlannedDays', the estimate is potentially too large.
        // Use the absence days to subtract one estimated day if a user is absent during the planning period of a planned item.
        // The array structure looks like this:
        // <assignee_id> => <planning_value> => [planned backlog items]
        $plannedItemsPerUser = [];
        foreach ($this->sprint->backlogItems as $backlogItem) {
            $plannedItemsPerUser[$backlogItem->assignee_id][$backlogItem->getPlanningValue()][] = $backlogItem;
            ksort($plannedItemsPerUser[$backlogItem->assignee_id]);
        }

        $absencePerUser = [];
        $absenceItems = $this->sprint->absenceItems;
        foreach ($absenceItems as $absenceItem) {
            $absencePerUser[$absenceItem->assignee_id][] = $absenceItem->getPlanningValue();
            sort($absencePerUser[$absenceItem->assignee_id]);
        }

        $longEstimates = [];
        foreach ($plannedItemsPerUser as $assignee => $items) {
            $previousPlanningValue = 0;
            foreach ($items as $planningValue => $backlogItems) {
                $estimateInDays = $planningValue - $previousPlanningValue;

                // For every day of absence between the previous planned item and the current one, reduce the estimate in days by one.
                if(key_exists($assignee, $absencePerUser)) {
                    $absenceForUser = $absencePerUser[$assignee];
                    foreach ($absenceForUser as $absenceDayPlanningValue) {
                        if ($absenceDayPlanningValue > $previousPlanningValue &&
                            $absenceDayPlanningValue <= $planningValue) {
                            $estimateInDays--;
                        }
                    }
                }

                if($estimateInDays > $this->maxPlannedDays) {
                    $longEstimates = array_merge($longEstimates, $backlogItems);
                }
                $previousPlanningValue = $planningValue;
            }
        }

        if(empty($longEstimates)) {
            return [];
        } else {
            $projectNumbers = implode(", #", array_map(fn($backlogItem): int => $backlogItem->project_number, $longEstimates));
            return array("SP-02" => "The following planned item(s) take more than $this->maxPlannedDays days to complete: #$projectNumbers. Consider splitting them up in smaller tasks.");
        }
    }
}
