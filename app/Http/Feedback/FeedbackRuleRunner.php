<?php

namespace App\Http\Feedback;

use App\Models\DailyFeedback;
use App\Models\SprintPlanningFeedback;
use Illuminate\Support\Facades\Log;

abstract class FeedbackRuleRunner
{
    private array $rules;

    /**
     * Create a new runner with zero or more rules already added.
     *
     * @param FeedbackRule ...$rules The rules to add
     */
    protected function __construct(FeedbackRule ...$rules)
    {
        $this->rules = $rules;
    }

    /**
     * Add one or more rule instances to the runner to run later.
     *
     * @param FeedbackRule ...$rules The rules to add
     * @return $this Returns itself so the method can be called subsequently
     */
    protected function addRule(FeedbackRule ...$rules): FeedbackRuleRunner {
        $this->rules = array_merge($this->rules, $rules);
        return $this;
    }

    /**
     * Run all the rules added to the runner so far
     *
     * @return SprintPlanningFeedback[] | DailyFeedback[] The array with all feedback items
     */
    public function run(): array {
        $allFeedback = [];
        foreach ($this->rules as $rule) {
            $allFeedback = array_merge($allFeedback, $rule->run());
        }
        return $allFeedback;
    }
}
