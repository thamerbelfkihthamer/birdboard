<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskTest extends TestCase
{
    
    use RefreshDatabase;


    /** @test */
    public function it_belongs_to_project()
    {
    	$project  = factory('App\Project')->create();

    	$task = factory(Task::class)->create(['project_id' => $project->id]);

    	$this->assertInstanceOf('App\Project', $task->project);
    }

    /** @test */
    public function it_can_be_completed()
    {
    	$task = factory(Task::class)->create();

    	$this->assertFalse($task->completed);

    	$task->complete();

    	$this->assertTrue($task->completed);
    }

    /** @test */
    public function it_can_be_mark_as_incomplete()
    {
    	$task = factory(Task::class)->create();

    	$this->assertFalse($task->completed);

    	$task->complete();
    	$task->incomplete();

    	$this->assertFalse($task->completed);
    }

    /** @test */
    public function it_morph_to_many_activities()
    {
    	$task = factory(Task::class)->create();

    	$this->assertInstanceOf(Collection::class, $task->activities);
    }

}
