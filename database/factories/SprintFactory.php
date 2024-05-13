<?php

namespace Database\Factories;

use App\Models\Project;
use DateTime;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sprint>
 */
class SprintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sprint_number' => 1,
            'project_id' => Project::all()->random()->id,
            'planned_sprint_start' => new \DateTime(),
        ];
    }
}
