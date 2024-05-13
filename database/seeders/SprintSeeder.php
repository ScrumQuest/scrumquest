<?php

namespace Database\Seeders;

use App\Models\Sprint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SprintSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sprint::factory(1)->create();
    }
}
