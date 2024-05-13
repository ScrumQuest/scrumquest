<?php

namespace App\Http\Feedback\Daily;

use App\Models\DailyFeedback;
use App\Models\Sprint;
use Illuminate\Http\Request;

class MarkDailyFeedbackFixed
{

    public function run(Request $request) {
        $token = $request->header('auth-token');
        $checkToken = env('DAILY_FEEDBACK_TOKEN', 'default');
        if($token !== $checkToken || $checkToken === 'default') {
            abort(404);
        }

        $sprints = Sprint::all();

        foreach ($sprints as $sprint) {
            // Gather the feedback left in the sprint plan, so that previous feedback can be marked as fixed.
            $runner = new DailyFeedbackRunner($sprint);
            $feedback = $runner->run();
            $fixedFeedback = DailyFeedback::where('sprint_id', $sprint->id)->whereNotIn('feedback_id', array_keys($feedback))->get();
            foreach ($fixedFeedback as $fixedFeedbackItem) {
                $fixedFeedbackItem->fixed = true;
                $fixedFeedbackItem->save();
            }
        }

        return response()->json(['success' => 'success'], 200);;
    }
}
