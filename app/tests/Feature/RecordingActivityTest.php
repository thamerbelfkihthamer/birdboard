<?php

namespace Tests\Feature;

use App\Activity;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Facades\Tests\Setup\ProjectFactory;
use App\Task;

class RecordingActivityTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function new_project_is_created()
    {
        $project = ProjectFactory::create();

        tap($project->activities->last(), function($activity) {
            $this->assertEquals(1, $activity->count());

            $this->assertEquals('created_project', $activity->description);

            $this->assertNull($activity->changes);

        });
    }


    /** @test */
    public function a_project_is_updated()
    {
        //$this->withoutExceptionHandling();

        $project = ProjectFactory::create();

        $orginalTitle = $project->title;

        $project->update(['title' => 'new title']);

        $this->assertEquals(2, $project->activities->count());

        tap($project->activities->last(), function($activity) use ($orginalTitle) {

            $this->assertEquals('updated_project', $activity->description);

            $expected = [
                'before' => ['title' => $orginalTitle],
                'after' => ['title' => 'new title']
            ];

            $this->assertEquals($expected, $activity->changes);

        });
    }

    /** @test */
    public function a_task_is_created()
    {
        //$this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $project->addTask('add new task');

        $this->assertEquals(2, $project->activities->count());

        $this->assertInstanceOf(Task::class, $project->tasks[0]->activities[0]->subject);
        $this->assertEquals('App\Task', $project->tasks[0]->activities[0]->subject_type);
    }

    /** @test */
    public function a_task_is_completed()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->assertEquals(3, $project->activities->count());

        $this->assertEquals(2, $project->tasks[0]->activities->count());
    }

    /** @test */
    public function a_task_is_incompleted()
    {

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => 'changed',
            'completed' => true
        ]);

        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => 'changed',
            'completed' => false
        ]);

        $this->assertDatabaseHas('activities', ['description' => 'incompleted_task']);

        $this->assertEquals(4, $project->activities->count());

        $this->assertEquals(3, $project->tasks[0]->activities->count());

    }

    /** @test */
    public function a_task_is_deleted()
    {
        $this->withoutExceptionHandling();

        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch('/tasks/delete/'. $project->tasks[0]->id);

        $this->assertEquals(3, $project->activities->count());

        $this->assertDatabaseHas('activities', ['description' => 'deleted_task']);

        $this->assertEquals(2, $project->tasks[0]->activities->count());
    }
}
