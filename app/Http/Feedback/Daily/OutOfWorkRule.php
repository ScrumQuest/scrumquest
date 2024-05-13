<?php

namespace App\Http\Feedback\Daily;

use App\Models\Sprint;

class OutOfWorkRule implements \App\Http\Feedback\FeedbackRule
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
        // Only provide this feedback if there is time left in the sprint
        $today = new \DateTime('midnight');
        $lastWorkDay = $this->sprint->getLastSprintWorkday();
        $difference = $lastWorkDay->diff($today);
        $daysDifference = $difference->days * ($difference->invert ? -1 : 1); // ->days is always positive...
        if($daysDifference > 0) {
            return [];
        }

        $itemsPerUser = [];

        // Create an entry in the array for every project member
        $allMembers = $this->sprint->project->users;
        foreach ($allMembers as $member) {
            $itemsPerUser[$member->id] = [];
        }

        // Add the non-completed items to the array per project member
        foreach ($this->sprint->backlogItems as $backlogItem) {
            if(!$backlogItem->completed) {
                $itemsPerUser[$backlogItem->assignee_id][] = $backlogItem;
            }
        }

        $membersWithEmptyLane = [];
        foreach ($itemsPerUser as $user => $items) {
            if(count($items) == 0) {
                 $membersWithEmptyLane[] = $user;
            }
        }

        // Retrieve the users names from their ID
        $users = $allMembers->whereIn('id', $membersWithEmptyLane);
        $membersWithEmptyLane = [];
        foreach ($users as $user) {
            $membersWithEmptyLane[] = $user->name;
        }

        if(!empty($membersWithEmptyLane)) {
            $membersAsString = implode(' & ', $membersWithEmptyLane);
            return ['DS-03' => "Team member(s) '${membersAsString}' have only completed or no items in their lane. Consider adding more work to the sprint or sharing the current load to ensure the sprint content gets completed efficiently."];
        }
        return [];
    }
}
