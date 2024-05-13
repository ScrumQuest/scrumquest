<?php

namespace App\Http\Feedback\SprintPlanning;

use App\Models\Sprint;
use App\Models\User;

class TeamMemberTimeAllocationRule implements \App\Http\Feedback\FeedbackRule
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
        $absenceItems = $this->sprint->absenceItems;
        $absencePerUser = [];
        foreach ($absenceItems as $absenceItem) {
            $assignee = $absenceItem->assignee->id;
            isset($absencePerUser[$assignee]) ? $absencePerUser[$assignee]++ : $absencePerUser[$assignee] = 1;
        }

        $project = $this->sprint->project;
        $expectedAbsence = $project->weeks_in_sprint * (5 - $project->expected_workdays_per_week);

        $feedback = [];
        foreach ($absencePerUser as $assignee_id => $absenceDays) {
            if($absenceDays > $expectedAbsence) {
                $assignee = User::findOrFail($assignee_id);
                $feedback["SP-03-$assignee->id"] = "$assignee->name has more planned absence than expected for this project. The expected workload for this project is $project->expected_workdays_per_week days per week. Keep in mind that everyone is expected to spend roughly the same amount of time on the project.";
            } else if($absenceDays < $expectedAbsence) {
                $assignee = User::findOrFail($assignee_id);
                $feedback["SP-03-$assignee->id"] = "$assignee->name has less planned absence than expected for this project. The expected workload for this project is $project->expected_workdays_per_week days per week. Keep in mind that everyone is expected to spend roughly the same amount of time on the project.";
            }
        }

        return $feedback;
    }
}
