<?php

namespace App\Http\Feedback\Daily;

use App\Http\Feedback\SprintPlanning\BacklogDetailRule;
use App\Http\Feedback\SprintPlanning\EstimationSizeRule;
use App\Http\Feedback\SprintPlanning\SprintAtCapacityRule;
use App\Http\Feedback\SprintPlanning\TeamMemberTimeAllocationRule;
use App\Models\DailyFeedback;
use App\Models\Sprint;

class DailyFeedbackRunner extends \App\Http\Feedback\FeedbackRuleRunner
{
    public function __construct(Sprint $sprint)
    {
        parent::__construct(new BacklogItemsOnTrackRule($sprint),
            new PostponedBacklogItems($sprint),
            new OutOfWorkRule($sprint),

            /** Sprint planning feedback (DS-04) */
            new BacklogDetailRule($sprint),
            new EstimationSizeRule($sprint),
            new SprintAtCapacityRule($sprint));
    }
}
