<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use Illuminate\Database\Eloquent\Collection;

class ProjectTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_has_a_path()
    {
    	$project = factory('App\Project')->create();


    	$this->assertEquals('/projects/' . $project->id, $project->path());
    }

    /**
      @test
    */
    public function it_belongs_to_owner()
    {
    	$project = factory('App\Project')->create();

    	$this->assertInstanceOf('App\User', $project->owner);
    }

    /** @test */
    public function it_has_many_tasks()
    {
        $project = factory(Project::class)->create();
        $this->assertInstanceOf(Collection::class, $project->tasks);
    }

    /** @test */
    public function it_can_add_a_task()
    {
        $project = factory(Project::class)->create();

        $project->addTask('task');

        $this->assertCount(1, $project->tasks);
    }

    /** @test */
    public function it_has_many_activities()
    {
        $project = factory(Project::class)->create();
        $this->assertInstanceOf(Collection::class, $project->activities);
    }

    /** @test */
    public function it_can_record_acticity()
    {
        $project = factory(Project::class)->create();

        $project->recordActivity('created');

        $this->assertDatabaseHas('activities', ['description' => 'created_project']);
    }

    /** @test */
    public function it_can_invite_a_user()
    {
        $project = factory(Project::class)->create();

        $project->invite($user = factory(User::class)->create());

        $this->assertTrue($project->members->contains($user));

        $this->assertDatabaseHas('project_members', ['project_id' => $project->id, 'member_id' => $user->id]);
    }

    /** @test */
    public function it_has_many_members()
    {
        $project = factory(Project::class)->create();

        $this->assertInstanceOf(Collection::class, $project->members);
    }


}

