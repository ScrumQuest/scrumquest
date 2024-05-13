<?php

namespace App\Http\Feedback\SprintPlanning;

use App\Models\BacklogItem;
use App\Models\Sprint;

class BacklogDetailRule implements \App\Http\Feedback\FeedbackRule
{
    private string $userStoryRegexEN = "/As\s?a\s?(.+),?I\s?(want\s?to|should\s?be\s?able\s?to)(.+)(so that)(.+)/i";
    private string $userStoryRegexNL = "/Als\s?(.+),?(wil\s?|zou\s?)\s?ik(.+)(zodat)(.+)/i";

    private Sprint $sprint;

    function __construct(Sprint $sprint) {
        $this->sprint = $sprint;
    }

    /**
     * @inheritDoc
     */
    public function run(): array
    {
        $feedbackItems = [];
        $backlogItems = $this->sprint->backlogItems;

        $incorrectTitles = [];
        $incorrectDescriptions = [];
        foreach ($backlogItems as $backlogItem) {
            if(!$this->titleCorrect($backlogItem)) {
                $incorrectTitles[] = $backlogItem->project_number;
            }

            if(!$this->descriptionCorrect($backlogItem)) {
                $incorrectDescriptions[] = $backlogItem->project_number;
            }
        }

        if(!empty($incorrectTitles)) {
            $projectNumberAsString = implode(", #", $incorrectTitles);
            $feedbackItems['SP-04-UserStory'] = "The title of planned item(s) #$projectNumberAsString is not formatted like a user story. A user story should be formatted like: 'As a ..., I want to ... so that ...' in English, or 'Als ..., wil ik ..., zodat ...' in Dutch";
        }

        if(!empty($incorrectDescriptions)) {
            $projectNumberAsString = implode(", #", $incorrectTitles);
            $feedbackItems['SP-04-Description'] = "The description of planned item(s) #$projectNumberAsString seems to contain little detail. Consider clarifying these items(s).";
        }

        return $feedbackItems;
    }

    private function titleCorrect(BacklogItem $backlogItem): bool {
        return preg_match($this->userStoryRegexEN, $backlogItem->title) ||
            preg_match($this->userStoryRegexNL, $backlogItem->title);
    }

    private function descriptionCorrect(BacklogItem $backlogItem): string | null {
        $wordCount = str_word_count(strip_tags($backlogItem->description), 0);
        return $wordCount >= 30;
    }
}
