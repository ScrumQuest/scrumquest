<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\AbsenceItem;
use App\Models\BacklogItem;
use App\Models\Project;
use App\Models\Sprint;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    private $projectNumberIncrement = 1;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->createProject('23/24 OOP Team 1', 1);
        $this->createProject('23/24 OOP Team 2', 2);
        $this->createProject('23/24 OOP Team 3', 4);
    }

    private function createProject(string $name, int $weeksInSprint) {
        $users = User::factory(4)->create();
        $project = Project::factory()
            ->hasAttached($users)
            ->create([
                'name' => $name,
                'weeks_in_sprint' => $weeksInSprint,
            ]);

        $this->createSprintsForProject($project);
        $this->addBacklogItemsToProject($project);
        $this->projectNumberIncrement = 1;
    }

    private function createSprintsForProject(Project $project) {
        $sprints = Sprint::factory(3)
            ->for($project)
            ->sequence(fn ($sequence) => ['sprint_number' => $sequence->index])
            ->create();

        $plannedStart = new \DateTime('last monday');
        $daysInSprint = 7 * $project->weeks_in_sprint;
        foreach ($sprints as $sprint) {
            $sprint->update([
                'planned_sprint_start' => $plannedStart
            ]);

            $plannedStart->modify("+$daysInSprint days");

            $this->addBacklogItemsToSprint($project, $sprint);
            $this->addAbsenceItemsToSprint($project, $sprint);
        }

        $sprintStart = new \DateTime('last monday');
        $sprintFinished = new \DateTime('last monday');
        $sprintFinished->modify("+$daysInSprint days");
        $sprints[0]->update([
            'sprint_start' => $sprintStart,
            'sprint_finished' => $sprintFinished,
        ]);
        $sprints[1]->update([
            'sprint_start' => $sprintFinished,
        ]);
    }

    private function addBacklogItemsToProject(Project $project) {
        BacklogItem::factory(10)
            ->for($project)
            ->sequence(fn ($sequence) => ['project_number' => $this->projectNumberIncrement++])
            ->create();
    }

    private function addBacklogItemsToSprint(Project $project, Sprint $sprint) {
        $items = BacklogItem::factory(10)
            ->for($project)
            ->sequence(
                ['completed' => true],
                ['completed' => false],
            )
            ->sequence(fn ($sequence) => ['project_number' => $this->projectNumberIncrement++])
            ->create();

        foreach ($items as $item) {
            $item->plan($sprint->id,
                fake()->numberBetween(1, $project->weeks_in_sprint),
                $project->users->random()->id,
                fake()->numberBetween(1, 5),
            );
        }
    }

    private function addAbsenceItemsToSprint(Project $project, Sprint $sprint) {
        $items = AbsenceItem::factory(10)
            ->for($sprint)
            ->make();

        foreach ($items as $item) {
            $item->week_in_sprint = fake()->numberBetween(1, $project->weeks_in_sprint);
            $item->day_in_week = fake()->numberBetween(1, 5);
            $item->assignee_id = $project->users->random()->id;
            $item->save();
        }
    }
}
