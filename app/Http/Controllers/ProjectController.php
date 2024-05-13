<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Sprint;
use DateTime;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $allowedProjects = [];
        foreach (Project::all() as $project) {
            if($user->can('view', $project)) {
                $project->amountOfSprints = count($project->sprints);
                $allowedProjects[] = $project;
            }
        }

        return view('projects.index', [
            'projects' => $allowedProjects,
            'user' => $user,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $today = new DateTime();
        return view('projects.create', [
            'today' => $today->format('Y-m-d'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $data = $request->validated();
        // Emphasize the default
        if(is_null($data['expected_workdays_per_week'])) {
            $data['expected_workdays_per_week'] = 5;
        }
        $project = Project::create($data);

        $project->users()->attach($request->user());
        $project->save();

        if (is_null($data['amount_of_sprints'])) {
            $data['amount_of_sprints'] = 1;
        }
        $firstSprintDay = new DateTime($data['first_sprint_day']);

        for ($sprintNumber = 0; $sprintNumber < $data['amount_of_sprints']; $sprintNumber++) {
            Sprint::create([
                'sprint_number' => $sprintNumber,
                'project_id' => $project->id,
                'planned_sprint_start' => $firstSprintDay,
            ]);

            $firstSprintDay->modify("+{$project->getNumberOfDaysInSprint()} days");
        }


        return redirect(route('projects.show', $project));
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        return view('projects.show', [
            'project' => $project
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        abort(404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project)
    {
        abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        abort(404);
    }

    /**
     * Add or remove the provided user as supervisor to the specified project
     * If the user is already supervisor, they will be removed, otherwise they will be added
     */
    public function supervisor(Request $request, Project $project, User $user) {
        if($request->user()->cannot('update', $project)) {
            abort(403);
        }

        if($project->supervisors->contains($user)) {
            $project->supervisors()->detach($user);
        } else {
            $project->supervisors()->attach($user);
        }

        return redirect(route('collaborators.show', $project));
    }
}
