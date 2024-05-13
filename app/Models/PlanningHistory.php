<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanningHistory extends Model
{
    use HasFactory;

    protected $fillable = ['planned_date'];

    public function backlogItem(): BelongsTo
    {
        return $this->belongsTo(BacklogItem::class);
    }
}
