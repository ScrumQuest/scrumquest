<?php

namespace App\Http\Feedback\SprintPlanning;

use App\Http\Feedback\FeedbackRuleRunner;
use App\Models\Sprint;

class SprintPlanningFeedbackRunner extends FeedbackRuleRunner
{

    public function __construct(Sprint $sprint)
    {
        parent::__construct(new SprintStartDateRule($sprint),
            new BacklogDetailRule($sprint),
            new SprintAtCapacityRule($sprint),
            new EstimationSizeRule($sprint),
            new TeamMemberTimeAllocationRule($sprint));
    }
}
