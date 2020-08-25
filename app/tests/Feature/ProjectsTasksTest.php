<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use App\Task;
use Facades\Tests\Setup\ProjectFactory;

class ProjectsTasksTest extends TestCase
{
    
    use RefreshDatabase;


    /** @test */
    public function guest_cannot_manage_tasks()
    {
        $project = factory(Project::class)->create();

        $task =  $project->addTask('new task');

        $this->post($project->path().'/tasks')->assertRedirect('login');

        $this->patch('/tasks/'. $task->id, [
            'body' => 'changed',
            'completed' => true
        ])->assertRedirect('login');
        
        $this->patch('/tasks/delete/'. $task->id)->assertRedirect('login');

    }

    /**
     @test
    */
    public function a_project_can_have_tasks()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->post($project->path(). '/tasks', $task = ['body' => 'new task']);

        $this->assertDatabaseHas('tasks', $task);

        $this->get($project->path())
                ->assertSee('new task');
    }

    /** @test */
    public function it_require_a_body()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();
        
        $task = factory(Task::class)->raw(['body' => '']);

        $this->post($project->path().'/tasks', $task)->assertSessionHasErrors('body');
    }

    /** @test */
    public function authenticated_user_cannot_add_a_task_to_a_project_does_not_own()
    {
        $project = factory(Project::class)->create();

        $this->signIn();

        $this->post($project->path().'/tasks', ['body' => 'new task'])->assertStatus(403);
    }


    /** @test */
    public function a_task_can_be_updated()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();


        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => 'changed',
        ]);

        $this->assertDatabaseHas('tasks', ['body' => 'changed']);
        $this->get($project->path())->assertSee('changed');
    }


    /** @test */
    public function a_task_can_be_completed()
    {
        $project  = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => $project->tasks[0]->body,
            'completed' => true
        ]);

        $this->assertDatabaseHas('tasks', ['completed' => true]);
    }

    /** @test */
    public function a_task_can_be_incompleted()
    {
        $project  = ProjectFactory::ownedBy($this->signIn())->withTasks(1)->create();

        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => $project->tasks[0]->body,
            'completed' => true
        ]);

        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => $project->tasks[0]->body,
            'completed' => false
        ]);

        $this->assertDatabaseHas('tasks', ['completed' => false]);
    }


    /** @test */
    public function authenticated_user_cannot_update_a_task_does_not_own()
     {
        $this->signIn();

        $project = ProjectFactory::withTasks(1)->create();

        $this->patch('/tasks/'. $project->tasks[0]->id, [
            'body' => 'changed',
            'completed' => true
        ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['body' => 'changed']);
    }

}
