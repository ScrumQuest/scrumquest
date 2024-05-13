<?php

namespace App\Http\Feedback;

interface FeedbackRule
{
    /**
     * The run method to be implemented by each specific feedback rule
     *
     * @return string[] The array of strings with feedback
     */
    public function run(): array;
}
