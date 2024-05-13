<?php

namespace App\Http\Feedback\Daily;

use App\Models\Sprint;

class PostponedBacklogItems implements \App\Http\Feedback\FeedbackRule
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
        $backlogItems = $this->sprint->backlogItems;

        $postponedProjectNumbers = [];
        foreach ($backlogItems as $backlogItem) {
            if($backlogItem->replans > 1) {
                $postponedProjectNumbers[] = $backlogItem->project_number;
            }
        }

        if(!empty($projectNumbersAsString)) {
            $projectNumbersAsString = implode(", #", $postponedProjectNumbers);
            return ["DS-01" => "Planned item(s) #$projectNumbersAsString have been postponed by the same team member multiple times. Consider helping each other with complex tasks."];
        }
        return [];
    }
}
