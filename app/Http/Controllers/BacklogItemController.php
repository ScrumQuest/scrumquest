<?php

namespace App\Http\Controllers;

use App\Enum\SprintStatus;
use App\Http\Requests\StoreBacklogItemRequest;
use App\Http\Requests\UpdateBacklogItemRequest;
use App\Models\BacklogItem;
use App\Models\PlanningHistory;
use App\Models\Project;
use App\Models\Sprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BacklogItemController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Project $project)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $backlogItems = BacklogItem::where('project_id', '=', $project->id)->get();
        return view('backlogitems.index', [
            'backlogItems' => $backlogItems,
            'project' => $project
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, Project $project)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        return view('backlogitems.create', [
            'project' => $project,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBacklogItemRequest $request, Project $project)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $data = $request->validated();

        $data['project_number'] = $project->backlogItems()->withTrashed()->max('project_number') + 1;
        $backlogItem = BacklogItem::create($data);
        $backlogItem->project()->associate($project);
        $backlogItem->save();

        return redirect(route('backlogitems.show', [$project, $backlogItem]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Project $project, BacklogItem $backlogitem)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        return view('backlogitems.show', [
            'project' => $project,
            'backlogItem' => $backlogitem,
            'plannedFor' => $backlogitem->plannedFor()?->format('d-m-Y'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Project $project, BacklogItem $backlogitem)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        return view('backlogitems.edit', [
            'project' => $project,
            'backlogItem' => $backlogitem,
            'plannedFor' => $backlogitem->plannedFor()?->format('d-m-Y'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBacklogItemRequest $request, Project $project, BacklogItem $backlogitem)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $data = $request->validated();
        if($request->has('completed')) {
            $data['completed'] = true;
        } else {
            $data['completed'] = false;
        }
        $backlogitem->update($data);

        return redirect(route('backlogitems.show', [$project, $backlogitem]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Project $project, BacklogItem $backlogitem)
    {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $backlogitem->delete();

        return redirect(route('backlogitems.index', [$project]));
    }

    /**
     * (Re)plan the specified backlog item.
     */
    public function plan(Request $request, Project $project, BacklogItem $backlogItem)
    {
        // TODO Validate the request (parameters used from the request should, in the very least, never be null)
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $dayInWeek = $request->day_in_week;
        $weekInSprint = $request->week_in_sprint;
        $sprintId = $request->sprint_id;
        if($dayInWeek === 'next') {
            $dayInWeek = 1;
        } elseif ($dayInWeek === 'previous') {
            $dayInWeek = 5;
        }

        // You can't update the plan of a finished sprint anymore
        $sprint = Sprint::findOrFail($sprintId);
        if($sprint->status() === SprintStatus::Finished) {
            abort(500);
        }

        $backlogItem->plan($sprintId, $weekInSprint, $request->assignee_id, $dayInWeek);

        $planningHistory = new PlanningHistory();
        $planningHistory->planned_date = $backlogItem->plannedFor();
        $planningHistory->backlogItem()->associate($backlogItem);
        $planningHistory->save();

        return response()->json([
            'avatar_link' => $backlogItem->assignee->avatar_link,
            'completed' => $backlogItem->completed,
            'on_track' => $backlogItem->onTrack()]);
    }

    public function unPlan(Request $request, Project $project, BacklogItem $backlogItem) {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        // You can't update the plan of a finished sprint anymore
        if($backlogItem->sprint->status() === SprintStatus::Finished) {
            abort(500);
        }

        $backlogItem->unPlan();

        return $backlogItem;
    }

    public function markComplete(Request $request, Project $project, BacklogItem $backlogItem) {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $backlogItem->update([
            'completed' => true,
        ]);

        return response()->json([
            'completed' => $backlogItem->completed,
            'on_track' => $backlogItem->onTrack(),
        ]);
    }

    public function markIncomplete(Request $request, Project $project, BacklogItem $backlogItem) {
        if ($request->user()->cannot('view', $project)) {
            abort(403);
        }

        $backlogItem->update([
            'completed' => false,
        ]);

        return response()->json([
            'completed' => $backlogItem->completed,
            'on_track' => $backlogItem->onTrack(),
        ]);
    }
}
