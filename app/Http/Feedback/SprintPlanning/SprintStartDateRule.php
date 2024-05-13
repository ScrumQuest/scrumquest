<?php

namespace App\Http\Feedback\SprintPlanning;

use App\Http\Feedback\FeedbackRule;
use App\Models\Project;
use App\Models\Sprint;

class SprintStartDateRule implements FeedbackRule
{
    private Sprint $sprint;

    function __construct(Sprint $sprint) {
        $this->sprint = $sprint;
    }

    /**
     * @inheritDoc
     */
    public function run(): array
    {
        $today = new \DateTime('midnight');
        $plannedSprintStart = new \DateTime($this->sprint->planned_sprint_start);

        if($today > $plannedSprintStart) {
            return ["SP-05" => "You are starting your sprint later than planned. Try to start your sprint on the first day."];
        } else if($today < $plannedSprintStart) {
            return ["SP-05" => "You are starting your sprint earlier than planned. Try to start your sprint on the first day."];
        } else {
            return [];
        }
    }
}
