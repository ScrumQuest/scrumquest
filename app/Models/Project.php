<?php

namespace App\Models;

use App\Enum\SprintStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weeks_in_sprint',
        'expected_workdays_per_week',
    ];

    public function getNumberOfDaysInSprint(): int {
        return 7 * $this->weeks_in_sprint;
    }

    /**
     * Get the sprint for this project that is currently in progress.
     * Returns null if no sprint is in progress
     *
     * @return Sprint|null
     */
    public function getInProgressSprint(): Sprint | null {
        $sprints = $this->sprints;
        foreach ($sprints as $sprint) {
            if($sprint->status() == SprintStatus::Progress) {
                return $sprint;
            }
        }
        return null;
    }

    public function getNextAvailableSprint(): Sprint | null {
        $sprints = $this->sprints;
        foreach ($sprints as $sprint) {
            if($sprint->status() == SprintStatus::Progress || $sprint->status() == SprintStatus::Planning) {
                return $sprint;
            }
        }
        return null;
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_user');
    }

    public function supervisors(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'project_supervisor');
    }

    public function backlogItems(): HasMany
    {
        return $this->hasMany(BacklogItem::class);
    }

    public function sprints(): HasMany
    {
        return $this->hasMany(Sprint::class);
    }
}
