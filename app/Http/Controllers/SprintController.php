<?php

namespace App\Http\Controllers;

use App\Enum\SprintStatus;
use App\Http\Feedback\Daily\DailyFeedbackRunner;
use App\Http\Feedback\SprintPlanning\SprintPlanningFeedbackRunner;
use App\Http\Requests\StoreSprintRequest;
use App\Http\Requests\UpdateSprintRequest;
use App\Models\AbsenceItem;
use App\Models\BacklogItem;
use App\Models\DailyFeedback;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\SprintPlanningFeedback;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SprintController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSprintRequest $request, Project $project)
    {
        if($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $lastStoredSprint = $project->sprints()->orderBy('planned_sprint_start',  'desc')->first();
        $lastPlannedStart = new \DateTime($lastStoredSprint->planned_sprint_start);

        Sprint::create([
            'sprint_number' => $lastStoredSprint->sprint_number + 1,
            'project_id' => $project->id,
            'planned_sprint_start' => $lastPlannedStart->modify("+{$project->getNumberOfDaysInSprint()} days"),
        ]);

        return redirect(route('projects.show', [$project]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project, Sprint $sprint, int $week = 1)
    {
        if($request->user()->cannot('view', $sprint)) {
            abort(403);
        }

        return $this->createShowView($project, $sprint, $week);
    }

    private function createShowView(Project $project, Sprint $sprint, int $week = 1) {
        $backlogItems = BacklogItem::where('sprint_id', '=', $sprint->id)
            ->where('week_in_sprint', '=', $week)
            ->get();
        $absenceItems = AbsenceItem::where('sprint_id', '=', $sprint->id)
            ->where('week_in_sprint', '=', $week)
            ->get();

        // Make a map of all backlog items per user per day
        $itemsPerDayPerUser = new \SplObjectStorage();
        foreach($sprint->project->users as $user) {
            // Only add users which are not supervisor for this project
            if(!$sprint->project->supervisors->contains($user)) {
                $itemsPerDayForUser = [];
                for ($i = 1; $i <= 5; $i++) {
                    $itemsPerDayForUser[$i] = [
                        'backlogitems' => $backlogItems->where('assignee_id', $user->id)->where('day_in_week', $i),
                        'absenceitems' => $absenceItems->where('assignee_id', $user->id)->where('day_in_week', $i),
                    ];
                }
                $itemsPerDayPerUser[$user] = $itemsPerDayForUser;
            }
        }

        $unplannedItems = BacklogItem::where('project_id', '=', $project->id)
            ->whereNull('sprint_id')
            ->where('completed', false)
            ->get();

        $weekDays = [];
        $weekDay = new \DateTime($sprint->planned_sprint_start);
        $weekDayModifier = 7 * ($week - 1);
        $weekDay->modify("+$weekDayModifier days");
        for ($i = 0; $i < 5; $i++) {
            $weekDays[] = $weekDay->format('l d-m');
            $weekDay->modify('+1 weekday');
        }

        // Gather daily feedback
        if($sprint->status() === SprintStatus::Progress) {
            $runner = new DailyFeedbackRunner($sprint);
            $dailyFeedback = $runner->run();
        }

        return view('sprints.show', ['sprint' => $sprint,
            'dailyFeedback' => $dailyFeedback ?? [],
            'itemsPerDayPerUser' => $itemsPerDayPerUser,
            'week' => $week,
            'unplannedItems' => $unplannedItems,
            'weekDays' => $weekDays,
        ]);
    }

    public function storeLatest(Request $request, Project $project) {
        $token = $request->header('auth-token');
        $checkToken = env('STORE_LATEST_SPRINT_TOKEN', 'default');
        if($token !== $checkToken || $checkToken === 'default') {
            abort(404);
        }

        $projectId = $project->id;
        $now = new \DateTime('now');
        $dateStamp = $now->format('Y-m-d');
        $nextSprint = $project->getNextAvailableSprint();
        $nextSprintNumber = $nextSprint->sprint_number;
        $path = "sprintviews/${projectId}/${dateStamp}_sprint_${nextSprintNumber}.html";

        Storage::disk('local')->put($path, $this->createShowView($project, $nextSprint, 1)->render());

        return response(null, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Project $project, Sprint $sprint)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        return view('sprints.edit', [
            'project' => $project,
            'sprint' => $sprint,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSprintRequest $request, Project $project, Sprint $sprint)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $data = $request->validated();
        $sprint->update($data);

        return redirect(route('projects.show', [$project]));
    }

    /**
     * Start the specified sprint
     */
    public function start(Request $request, Project $project, Sprint $sprint) {
        if($request->user()->cannot('update', $sprint)) {
            abort(403);
        }

        // The sprint must be in the correct state to be started
        if(!$sprint->canBeStarted()) {
            abort(404);
        }

        // Gather the feedback left in the sprint plan, so that previous feedback can be marked as fixed.
        $runner = new SprintPlanningFeedbackRunner($sprint);
        $feedback = $runner->run();
        $fixedFeedback = SprintPlanningFeedback::where('sprint_id', $sprint->id)->whereNotIn('feedback_id', array_keys($feedback))->get();
        foreach ($fixedFeedback as $fixedFeedbackItem) {
            $fixedFeedbackItem->fixed_at_sprint_start = true;
            $fixedFeedbackItem->save();
        }

        // Start the sprint
        $today = new \DateTime();
        $sprint->sprint_start = $today;
        $sprint->save();

        $plannedItems = $sprint->backlogItems;
        foreach ($plannedItems as $plannedItem) {
            $plannedItem->original_planned_date = $plannedItem->plannedFor();
            $plannedItem->save();
        }

        return redirect(route('sprints.show', [
            'project' => $project,
            'sprint' => $sprint,
            'weekNumber' => 1,
        ]));
    }

    /**
     * Finish the sprecified sprint
     */
    public function finish(Request $request, Project $project, Sprint $sprint) {
        if($request->user()->cannot('update', $sprint)) {
            abort(403);
        }

        // The sprint must be in the correct state to be finished
        if(!$sprint->canBeFinished()) {
            abort(404);
        }

        $today = new \DateTime();
        $sprint->sprint_finished = $today;
        $sprint->save();

        $this->completedItemsToBacklog($sprint);

        return redirect(route('sprints.show', [
            'project' => $project,
            'sprint' => $sprint,
            'weekNumber' => 1,
        ]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project, Sprint $sprint)
    {
        if($request->user()->cannot('delete', $sprint)) {
            abort(403);
        }

        if($sprint->status() === SprintStatus::Planning) {
            $this->completedItemsToBacklog($sprint);

            $sprint->delete();
        }

        return redirect(route('projects.show', [$project]));
    }

    private function completedItemsToBacklog(Sprint $sprint): void {
        $itemsInSprint = $sprint->backlogItems;
        foreach ($itemsInSprint as $item) {
            if(!$item->completed) {
                $item->unPlan();
            }
        }
    }
}
