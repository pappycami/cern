<?php
namespace Database\Seeders;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Seeder;
use function rand;


class ProjectWithTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Project::factory()
            ->count(5)
            ->create()
            ->each(function ($project) {
                $project->tasks()->createMany(
                    Task::factory()->count(rand(2, 5))->make()->toArray()
                );
            });
    }
}
