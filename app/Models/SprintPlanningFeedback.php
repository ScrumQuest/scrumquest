<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SprintPlanningFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'sprint_id',
        'feedback_id',
        'feedback',
    ];

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }
}
