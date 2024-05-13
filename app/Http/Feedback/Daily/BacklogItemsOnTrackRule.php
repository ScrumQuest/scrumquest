<?php

namespace App\Http\Feedback\Daily;

use App\Models\DailyFeedback;
use App\Models\Sprint;

class BacklogItemsOnTrackRule implements \App\Http\Feedback\FeedbackRule
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
        $feedback = array();
        $backlogItems = $this->sprint->backlogItems;

        foreach ($backlogItems as $backlogItem) {
            if(!$backlogItem->onTrack() && !$backlogItem->completed) {
                $itemNumber = $backlogItem->project_number;
                $feedback["DS-02"] = "Planned item #${itemNumber} was planned to be completed, but isn't finished yet. You should probably re-plan the item.";
            }
        }

        return $feedback;
    }
}
