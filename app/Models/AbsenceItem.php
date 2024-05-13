<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'day_in_week',
        'assignee_id',
        'week_in_sprint',
        'sprint_id',
    ];

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function getPlanningValue() {
        return (($this->week_in_sprint - 1) * 5) + $this->day_in_week;
    }
}
