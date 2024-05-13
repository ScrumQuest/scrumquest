<?php

namespace Database\Factories;

use App\Models\Sprint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AbsenceItem>
 */
class AbsenceItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'assignee_id' => User::all()->random(),
            'sprint_id' => Sprint::all()->random(),
            'week_in_sprint' => 1,
            'day_in_week' => 1,
        ];
    }
}
