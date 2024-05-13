<?php

namespace App\Http\Controllers;

use App\Enum\SprintStatus;
use App\Http\Feedback\Daily\DailyFeedbackRunner;
use App\Models\DailyFeedback;
use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DailyFeedbackController extends Controller
{
    /**
     * Provide the caller with daily feedback based on the sprint provided
     *
     * @param Request $request
     * @param Project $project
     * @param Sprint $sprint
     * @return JsonResponse
     */
    public function feedback(Request $request, Project $project, Sprint $sprint): JsonResponse
    {
        if ($request->user()->cannot('view', $sprint)) {
            abort(403);
        }

        if($sprint->status() !== SprintStatus::Progress) {
            abort(404);
        }

        $runner = new DailyFeedbackRunner($sprint);
        $feedback = $runner->run();
        $this->storeFeedback($sprint, $feedback);

        return response()->json(array_values($feedback));
    }

    private function storeFeedback(Sprint $sprint, array $feedback): void
    {
        foreach ($feedback as $id => $feedbackItem) {
            $dailyFeedback = DailyFeedback::firstOrCreate(
                [   'sprint_id' => $sprint->id,
                    'feedback_day' => new \DateTime('midnight'),
                    'feedback_id' => $id],
                ['feedback' => $feedbackItem]
            );

            // Count the amount of times the feedback has been provided.
            // Column starts at 0, so the first increment after creation will set it to 1.
            $dailyFeedback->repeats++;
            $dailyFeedback->save();
        }
    }
}
