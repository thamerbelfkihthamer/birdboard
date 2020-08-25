<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Project;
use  Facades\Tests\Setup\ProjectFactory;
use App\User;

class ManageProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;


      /** @test */
    public function guest_cannot_manage_project()
    {

        $project = factory('App\Project')->create();
        $user = factory(User::class)->create();

        $this->post('/projects', $project->toArray())->assertRedirect('login');

        $this->get('/projects')->assertRedirect('login');

        $this->get($project->path())->assertRedirect('login');

        $this->get('/projects/create')->assertRedirect('login');

        $this->get('/projects/edit/'. $project->id)->assertRedirect('login');

        $this->delete('/projects/'. $project->id)->assertRedirect('login');
    }


    /**
      @test
    */
    public function a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $this->get('/projects/create')->assertSee("create new project");

        // Arrange
        $attributes = [
             'title' => $this->faker->sentence,
             'description' => $this->faker->paragraph,
             'notes' => $this->faker->sentence
        ];

        // Act & Assert redirect
        $response = $this->post('/projects', $attributes);

        $project = Project::where($attributes)->first();

        $response->assertRedirect($project->path());

        $this->assertDatabaseHas('projects', $project->toArray());

    }



    /**
      @test
    */
    public function it_requires_a_title()
    {
        $this->signIn();

        $project = factory('App\Project')->raw(['title' => '']);

        $this->post('/projects', $project)->assertSessionHasErrors('title');
    }


    /**
      @test
    */
    public function it_requires_a_description()
    {
        $this->signIn();

        $project  = factory('App\Project')->raw(['description' => '']);

        $this->post('/projects', $project)->assertSessionHasErrors('description');
    }

    /**
      @test
    */
    public function only_authenticated_users_can_create_projects()
    {
        $project = factory('App\Project')->raw();

        $this->post('/projects', $project)->assertRedirect('login');
    }



    /**
      @test
    */
    public function a_user_can_view_their_project()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $project = factory('App\Project')->create(['owner_id' => auth()->user()->id ]);

        $this->get($project->path())
                ->assertSee($project->title)
                ->assertSee($project->description);
    }

    /**
      @test
    */
    public function an_authenticated_user_cannot_view_the_project_of_others()
    {
        $this->signIn();

        $project = factory('App\Project')->create();

        $this->get($project->path())->assertStatus(403);
    }

    /**
      @test
    */
    public function an_authendicated_user_cannot_view_all_the_projects_of_others()
    {
        factory('App\Project')->create();

        $this->signIn();

        factory('App\Project')->create(['owner_id' => auth()->user()->id]);

        $this->assertEquals(2, Project::all()->count());
        $this->assertEquals(1, auth()->user()->projects->count());
    }

    /**
     @test
    */
    public function an_authenticated_user_can_update_his_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->get('/projects/edit/'. $project->id)->assertSee("update project")->assertSee($project->title);


        $this->patch('/projects/update/'. $project->id,
            [
                'title' => 'this is new title',
                'description' => $project->description,
                'notes' => 'this is new note'
            ])
            ->assertRedirect('/projects');

        $this->get('/projects')->assertSee('this is new title');
    }

    /** @test */
    public function an_authenticated_user_can_update_notes_of_his_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->patch('/projects/update/'. $project->id, ['notes' => 'this is my new note']);

        $this->assertDatabaseHas('projects', ['notes' => 'this is my new note']);
    }

    /**
     @test
    */
    public function an_authenticated_user_cannot_update_the_projects_of_others()
    {
        $project = factory('App\Project')->create();

        $this->signIn();

        $this->get('/projects/edit/'. $project->id)->assertStatus(403);

        $this->patch('/projects/update/'. $project->id, $project->toArray())->assertStatus(403);
    }

    /** @test */
    public function an_authenticated_user_can_delete_his_project()
    {
        $project = ProjectFactory::ownedBy($this->signIn())->create();

        $this->delete('/projects/'. $project->id)->assertRedirect('projects');

        $this->assertEquals(0, auth()->user()->projects->count());

    }

    /** @test */
    public function an_authenticated_user_cannot_delete_the_projects_of_others()
    {
        $project = factory('App\Project')->create();

        $this->signIn();

        $this->delete('/projects/'. $project->id)->assertStatus(403);
    }

}
