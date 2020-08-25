<?php

use Illuminate\Database\Seeder;
use App\Task;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	factory(Task::class, 2)->create(['project_id' => 1]);
    	factory(Task::class, 3)->create(['project_id' => 2]);
    	factory(Task::class, 1)->create(['project_id' => 3]);
    	factory(Task::class, 5)->create(['project_id' => 3]);
    	factory(Task::class, 100)->create();
    }
}
