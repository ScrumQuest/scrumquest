<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyFeedback extends Model
{
    use HasFactory;

    protected $fillable = [
        'sprint_id',
        'feedback_day',
        'feedback_id',
        'backlog_item_id',
        'feedback',
    ];

    public function sprint(): BelongsTo
    {
        return $this->belongsTo(Sprint::class);
    }

    public function increaseRepetition() {
        $this->repeats++;
        $this->save();
    }
}
