<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCollaboratorRequest;
use App\Models\AbsenceItem;
use App\Models\BacklogItem;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollaboratorsController extends Controller
{
    /**
     * Show the users (collaborators) for this project
     */
    public function show(Request $request, Project $project): View {
        if ($request->user()->cannot('update', $project)) {
            abort(403);
        }

        $projectId = $project->id;
        $usersNotInProject = User::whereDoesntHave('projects', function ($query) use ($projectId) {
            $query->where('project_id', $projectId);
        })->orderBy('name')->get();

        return view('collaborators.show', [
            'project' => $project,
            'usersNotInProject' => $usersNotInProject,
        ]);
    }

    public function store(StoreCollaboratorRequest $request, Project $project) {
        if ($request->user()->cannot('update', $project)) {
            abort(403);
        }

        $data = $request->validated();
        $userToAdd = User::find($data['user_id']);

        $project->users()->attach($userToAdd);

        return redirect(route('collaborators.show', [$project]));
    }

    public function destroy(Request $request, Project $project, User $user) {
        if ($request->user()->cannot('update', $project)) {
            abort(403);
        }

        // Unplan the backlog items
        $assignedItems = BacklogItem::where('project_id', $project->id)
            ->where('assignee_id', $user->id)
            ->get();

        foreach ($assignedItems as $item) {
            $item->unPlan();
        }

        // Remove absence
        $absenceItems = AbsenceItem::whereIn('sprint_id', $project->sprints->pluck('id')->toArray())
            ->where('assignee_id', $user->id)
            ->get();
        foreach ($absenceItems as $item) {
            $item->delete();
        }

        // Remove user from project
        $project->users()->detach($user);

        return redirect(route('collaborators.show', [$project]));
    }
}
