<?php

namespace App\Http\Controllers;

use App\Http\Feedback\SprintPlanning\SprintPlanningFeedbackRunner;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\SprintPlanningFeedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SprintFeedbackController extends Controller
{
    /**
     * Provide the caller with feedback based on the sprint provided
     *
     * @param Request $request
     * @param Project $project
     * @param Sprint $sprint
     * @return JsonResponse
     */
    public function feedback(Request $request, Project $project, Sprint $sprint): JsonResponse
    {
        if($request->user()->cannot('view', $sprint)) {
            abort(403);
        }

        // The sprint must be in the correct state to be started
        if(!$sprint->canBeStarted()) {
            abort(404);
        }

        $runner = new SprintPlanningFeedbackRunner($sprint);
        $feedback = $runner->run();
        $this->storeFeedback($feedback, $sprint);

        return response()->json(array_values($feedback));
    }

    private function storeFeedback(array $feedback, Sprint $sprint): void {
        foreach ($feedback as $id => $feedbackItem) {
            $sprintPlanningFeedback = SprintPlanningFeedback::firstOrCreate(
                ['sprint_id' => $sprint->id,
                    'feedback_id' => $id],
                ['feedback' => $feedbackItem]
            );

            // Count the amount of times the feedback has been provided.
            // Column starts at 0, so the first increment after creation will set it to 1.
            $sprintPlanningFeedback->repeats++;
            $sprintPlanningFeedback->save();
        }
    }
}
