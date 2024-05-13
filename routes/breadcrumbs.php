<?php

use App\Models\BacklogItem;
use App\Models\Project;
use App\Models\Sprint;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('home', function (BreadcrumbTrail $trail): void {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('consent.show', function (BreadcrumbTrail $trail): void {
    $trail->push('Consent', route('consent.show'));
});

Breadcrumbs::for('registration', function (BreadcrumbTrail $trail): void {
    $trail->push('Registration', route('registration'));
});

Breadcrumbs::for('projects.index', function (BreadcrumbTrail $trail): void {
    $trail->parent('home');
    $trail->push('Projects', route('projects.index'));
});

Breadcrumbs::for('projects.show', function (BreadcrumbTrail $trail, Project $project): void {
    $trail->parent('projects.index');
    $trail->push($project->name, route('projects.show', $project));
});

Breadcrumbs::for('projects.create', function (BreadcrumbTrail $trail): void {
    $trail->parent('projects.index');
    $trail->push('Create', route('projects.create'));
});

Breadcrumbs::for('collaborators.show', function (BreadcrumbTrail $trail, Project $project): void {
    $trail->parent('projects.index');
    $trail->push($project->name, route('projects.show', $project));
    $trail->push('Collaborators', route('collaborators.show', $project));
});

Breadcrumbs::for('backlogitems.index', function (BreadcrumbTrail $trail, Project $project): void {
    $trail->parent('projects.show', $project);
    $trail->push('Backlogitems', route('backlogitems.index', $project));
});

Breadcrumbs::for('backlogitems.create', function (BreadcrumbTrail $trail, Project $project): void {
    $trail->parent('backlogitems.index', $project);
    $trail->push('Create', route('backlogitems.create', [$project]));
});

Breadcrumbs::for('backlogitems.show', function (BreadcrumbTrail $trail, Project $project, BacklogItem $backlogItem): void {
    $trail->parent('backlogitems.index', $project);
    $trail->push($backlogItem->id, route('backlogitems.show', [$project, $backlogItem]));
});

Breadcrumbs::for('backlogitems.edit', function (BreadcrumbTrail $trail, Project $project, BacklogItem $backlogItem): void {
    $trail->parent('backlogitems.show', $project, $backlogItem);
    $trail->push('Edit', route('backlogitems.edit', [$project, $backlogItem]));
});

Breadcrumbs::for('sprints.show', function (BreadcrumbTrail $trail, Project $project, Sprint $sprint, int $weekNumber): void {
    $trail->parent('projects.show', $project);
    if($weekNumber < 0) {
        $trail->push("Sprint {$sprint->sprint_number}", route('sprints.show', [$project, $sprint, $weekNumber]));
    } else {
        $trail->push("Sprint {$sprint->sprint_number} - week {$weekNumber}", route('sprints.show', [$project, $sprint, $weekNumber]));
    }
});

Breadcrumbs::for('sprints.storelatest', function (BreadcrumbTrail $trail, Project $project): void {
    $trail->parent('projects.show', $project);
    $trail->push("Latest sprint", route('sprints.storelatest', [$project]));
});

Breadcrumbs::for('sprints.edit', function (BreadcrumbTrail $trail, Project $project, Sprint $sprint): void {
    $trail->parent('sprints.show', $project, $sprint, -1);
    $trail->push('Edit', route('sprints.edit', [$project, $sprint]));
});

Breadcrumbs::for('profile.edit', function (BreadcrumbTrail $trail): void {
    $trail->push('Edit profile', route('profile.edit'));
});

Breadcrumbs::for('login', function (BreadcrumbTrail $trail): void {
    $trail->push('Login', route('login'));
});

Breadcrumbs::for('sprintexports.index', function (BreadcrumbTrail $trail, Project $project): void {
    $trail->push('Sprint exports', route('sprintexports.index', [$project]));
});
