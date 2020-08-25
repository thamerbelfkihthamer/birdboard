<?php

use Illuminate\Database\Seeder;
use App\Project;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(Project::class, 5)->create(['owner_id' => 1]);
    	factory(Project::class, 95)->create();
    }
}
