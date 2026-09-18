<?php
namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;

class ProjectWithTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->count(300)
            ->create()
            ->each(function ($project) {
                $project->tasks()->createMany(
                    Task::factory()->count(rand(1, 10))->make()->toArray()
                );
            });
    }
}
