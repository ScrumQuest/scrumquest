<?php

use App\Http\Controllers\AbsenceItemController;
use App\Http\Controllers\CollaboratorsController;
use App\Http\Controllers\ConsentController;
use App\Http\Controllers\DailyFeedbackController;
use App\Http\Controllers\ExportViewController;
use App\Http\Controllers\GitHubController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SprintController;
use App\Http\Controllers\BacklogItemController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SprintFeedbackController;
use App\Http\Controllers\UserController;
use App\Http\Feedback\Daily\MarkDailyFeedbackFixed;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('', [HomeController::class, 'show'])
    ->name('home');

/** Daily feedback fixed route */
Route::get('markdailyfeedbackfixed', [MarkDailyFeedbackFixed::class, 'run']);

/** Export the sprint show view for a given project */
Route::get('projects/{project}/sprints/storelatest', [SprintController::class, 'storeLatest'])
    ->name('sprints.storelatest');

/** Route(s) to view the exported sprint views */
Route::get('sprintexports/{project}', [ExportViewController::class, 'index'])
    ->name('sprintexports.index')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::get('sprintexports/{project}/{export}', [ExportViewController::class, 'show'])
    ->name('sprintexports.show')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** Consent routes */
Route::get('consent', [ConsentController::class, 'show'])
    ->name('consent.show')
    ->middleware('auth');
Route::put('consent', [ConsentController::class, 'update'])
    ->name('consent.update')
    ->middleware('auth');

/** Registration route */
Route::get('registration', function () {
    if(env('APP_REGISTRATION_PHASE', true)) {
        return view('registration', ['user' => \Illuminate\Support\Facades\Auth::user()]);
    } else {
        return redirect(route('projects.index'));
    }
})
    ->name('registration')
    ->middleware('auth', 'consent');

/** Sprint routes */
Route::get('projects/{project}/sprints/{sprint}/week/{weekNumber}', [SprintController::class, 'show'])
    ->name('sprints.show')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::post('projects/{project}/sprints', [SprintController::class, 'store'])
    ->name('sprints.store')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::get('projects/{project}/sprints/{sprint}/edit', [SprintController::class, 'edit'])
    ->name('sprints.edit')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::put('projects/{project}/sprints/{sprint}', [SprintController::class, 'update'])
    ->name('sprints.update')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::delete('projects/{project}/sprints/{sprint}', [SprintController::class, 'destroy'])
    ->name('sprints.destroy')
    ->middleware(['auth', 'consent', 'catch.registration']);

Route::put('projects/{project}/sprints/{sprint}/start', [SprintController::class, 'start'])
    ->name('sprints.start')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::put('projects/{project}/sprints/{sprint}/finish', [SprintController::class, 'finish'])
    ->name('sprints.finish')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** Sprint feedback routes */
Route::get('projects/{project}/sprints/{sprint}/feedback', [SprintFeedbackController::class, 'feedback'])
    ->name('sprints.feedback')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** Daily feedback routes */
Route::get('projects/{project}/sprints/{sprint}/dailyfeedback', [DailyFeedbackController::class, 'feedback'])
    ->name('sprints.dailyfeedback')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** Project routes */
Route::resource('projects', ProjectController::class)
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::put('/projects/{project}/supervisor/{user}', [ProjectController::class, 'supervisor'])
    ->name('project.supervisor')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** Collaborator routes */
Route::get('projects/{project}/collaborators', [CollaboratorsController::class, 'show'])
    ->name('collaborators.show')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::post('projects/{project}/collaborators', [CollaboratorsController::class, 'store'])
    ->name('collaborators.store')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::delete('projects/{project}/collaborators/{user}', [CollaboratorsController::class, 'destroy'])
    ->name('collaborators.destroy')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** Backlog item routes */
Route::resource('projects/{project}/backlogitems', BacklogItemController::class)
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::put('projects/{project}/backlogitems/{backlogItem}/plan', [BacklogItemController::class, 'plan'])
    ->name('backlogitems.plan')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::put('projects/{project}/backlogitems/{backlogItem}/unplan', [BacklogItemController::class, 'unPlan'])
    ->name('backlogitems.unplan')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::put('projects/{project}/backlogitems/{backlogItem}/markcomplete', [BacklogItemController::class, 'markComplete'])
    ->name('backlogitems.markcomplete')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::put('projects/{project}/backlogitems/{backlogItem}/markincomplete', [BacklogItemController::class, 'markIncomplete'])
    ->name('backlogitems.markincomplete')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** Absence item routes */
Route::post('projects/{project}/absenceitems', [AbsenceItemController::class, 'store'])
    ->name('absenceitems.store')
    ->middleware(['auth', 'consent', 'catch.registration']);
Route::delete('projects/{project}/absenceitems/{absenceItem}', [AbsenceItemController::class, 'destroy'])
    ->name('absenceitems.destroy')
    ->middleware(['auth', 'consent', 'catch.registration']);

/** User profile routes */
Route::get('profile', [UserController::class, 'edit'])
    ->name('profile.edit')
    ->middleware(['auth', 'consent']);
Route::put('profile', [UserController::class, 'update'])
    ->name('profile.update')
    ->middleware(['auth', 'consent']);

/** Authentication routes */
Route::get('auth/github/callback', [GitHubController::class, 'gitCallback']);
Route::get('auth/github', [GitHubController::class, 'gitRedirect']);
Route::get('login', [LoginController::class, 'login'])
    ->name('login');
Route::post('logout', [LoginController::class, 'logout'])
    ->name('logout');
