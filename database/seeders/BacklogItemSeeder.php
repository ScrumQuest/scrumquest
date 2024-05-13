<?php

namespace Database\Seeders;

use App\Models\BacklogItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BacklogItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        BacklogItem::factory(50)->create();
    }
}
