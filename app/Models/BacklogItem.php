<?php

namespace App\Models;

use App\Enum\SprintStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BacklogItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'project_number',
        'title',
        'description',
        'day_in_week',
        'assignee_id',
        'week_in_sprint',
        'sprint_id',
        'completed',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function plan(int $sprintId, int $weekInSprint, int $assigneeId, int $dayInWeek): void
    {
        $assigneeUnchanged = $this->assignee_id == $assigneeId;
        $this->update([
            'day_in_week' => $dayInWeek,
            'assignee_id' => $assigneeId,
            'week_in_sprint' => $weekInSprint,
            'sprint_id' => $sprintId,
        ]);

        $this->countRePlan($assigneeUnchanged);
    }

    public function unPlan(): void
    {
        $this->update([
            'day_in_week' => null,
            'assignee_id' => null,
            'week_in_sprint' => null,
            'sprint_id' => null,
        ]);
    }

    public function plannedFor(): \DateTime | null {
        if($this->sprint == null) {
            return null;
        }
        $plannedSprintStart = new \DateTime($this->sprint->planned_sprint_start);
        $weekMultiplier = ($this->week_in_sprint - 1) * 7;
        $weekDayAddition = $this->day_in_week - 1;
        // Add 7 days for each week of the sprint (0 for week 1, 7 for week 2 etc...)
        // Then add the day_in_week as weekdays (we don't work on weekends)
        return $plannedSprintStart->modify("+{$weekMultiplier} days")->modify("+{$weekDayAddition} weekdays");
    }

    public function onTrack(): bool {
        $today = new \DateTime('midnight');
        return $this->plannedFor() >= $today;
    }

    public function getPlanningValue() {
        return (($this->week_in_sprint - 1) * 5) + $this->day_in_week;
    }

    private function countRePlan(bool $assigneeUnchanged) {
        // Count the total number of replanning activities. When the assignee was changed, also record this
        $this->update([
            'total_replans' => $this->total_replans++,
        ]);
        if(!$assigneeUnchanged) {
            $this->update([
                'reassignments' => $this->reassignments++,
            ]);
        }

        // If the item is newly planned in the sprint, or the assigned team member changes, the replan counter is reset
        if($this->sprint->status() === SprintStatus::Planning || !$assigneeUnchanged) {
            $this->update([
                'replans' => 0,
            ]);
        } else if ($this->sprint->status() === SprintStatus::Progress) {
            // Only count the replan if the ticket wasn't updated for over an hour
            // It is only a replan if the assignee remains the same
            if($this->updated_at->diffInHours(Carbon::now('UTC')) > 1 && $assigneeUnchanged) {
                $this->update([
                    'replans' => $this->replans++,
                ]);
            }
        }
    }
}
