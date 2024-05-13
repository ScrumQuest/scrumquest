<?php

namespace App\Models;

use App\Enum\SprintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sprint extends Model
{
    use HasFactory;

    protected $fillable = [
        'sprint_number',
        'project_id', // To make mass creation of new sprints possible
        'planned_sprint_start',
        'sprint_start',
        'sprint_finished',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function absenceItems(): \Illuminate\Database\Eloquent\Relations\HasMany {
        return $this->hasMany(AbsenceItem::class);
    }

    public function backlogItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BacklogItem::class);
    }

    public function status() : SprintStatus | null {
        if(is_null($this->sprint_start) && is_null($this->sprint_finished)) {
            return SprintStatus::Planning;
        } elseif (!is_null($this->sprint_start) && is_null($this->sprint_finished)) {
            return SprintStatus::Progress;
        } elseif (!is_null($this->sprint_start) && !is_null($this->sprint_finished)) {
            return SprintStatus::Finished;
        } else {
            return null;
        }
    }

    /**
     * Returns true iff the sprint can be started.
     * A sprint can only be started if there is no other sprint in progress
     */
    public function canBeStarted() : bool {
        return $this->project->getInProgressSprint() == null;
    }

    /**
     * Returns true iff the sprint can be finished.
     * A sprint can only be finished if it has been started and not yet finished
     */
    public function canBeFinished() : bool {
        return $this->status() === SprintStatus::Progress;
    }

    public function getMaxPlanningValue() : int {
        return $this->project->weeks_in_sprint * 5;
    }

    public function getLastSprintWorkday(): \DateTime {
        $planned = new \DateTime($this->planned_sprint_start);

        // Add 5 weekdays per week in the sprint to find the start date of the next sprint
        // Then subtract one weekday to find the last day of the current sprint
        $modifier = ($this->project->weeks_in_sprint * 5) - 1;
        $planned->modify("+${modifier}weekdays");

        return $planned;
    }
}
