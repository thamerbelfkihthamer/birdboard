<?php

namespace Tests\Feature;

use App\Notifications\ProjectInviteUserNotification;
use App\Project;
use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\AnonymousNotifiable;

class ProjectInvitationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_manage_invitation()
    {
        $project = factory(Project::class)->create();

        $user = factory(User::class)->create();

        $this->post('/projects/invitation/'. $project->id, ['email' => $user->email])->assertRedirect('login');
    }

    /** @test */
    public function invited_users_can_update_project_details()
    {
        $project = ProjectFactory::create();

        $newUser = factory(User::class)->create();

        $project->invite($newUser);

        $this->signIn($newUser);
        $this->post(action('ProjectTasksController@store', $project), $task = ['body' => 'from new user' ]);

        $this->assertDatabaseHas('tasks', $task);
    }

    /** @test */
    public function a_user_can_see_all_projects_they_have_been_invited_to_on_their_dashboard()
    {
        // given we're sign In
        $user = $this->signIn();

        //and we've been invited to a project that was not created by us
        $project = factory(Project::class)->create();
        $project->invite($user);

        // when i visit my dashboard
        // i should see that project
        $this->get('/projects')
            ->assertSee(str_limit($project->title, 50));
    }

    /** @test */
    public function a_user_can_invite_another_user_to_his_project()
    {
        $this->withoutExceptionHandling();

        Notification::fake();

        Notification::assertNothingSent();

        $me = $this->signIn();

        $project = ProjectFactory::ownedBy($me)->create();

        $taylor = factory(User::class)->create();

        $this->post('/projects/invitation/'. $project->id, ['email' => $taylor->email])
            ->assertRedirect($project->path());

        Notification::assertSentTo(
            $taylor,
            ProjectInviteUserNotification::class,
            function ($notification, $channels) use ($project) {
                return $notification->project->id === $project->id;
            }
        );

        $this->get($project->path())->assertSee($taylor->name);

        $this->assertTrue($project->members->contains($taylor));
    }

    /** @test */
    public function a_user_cannot_invite_another_user_to_his_project_twice()
    {
        Notification::fake();

        $me = $this->signIn();

        $project = ProjectFactory::ownedBy($me)->create();

        $taylor = factory(User::class)->create();

        $this->post('/projects/invitation/'. $project->id, ['email' => $taylor->email])
            ->assertRedirect($project->path());


        $this->assertTrue($project->members->contains($taylor));

        $this->post('/projects/invitation/'. $project->id, ['email' => $taylor->email])
            ->assertSessionHasErrors('email', null, 'invitation');

        Notification::assertTimesSent(1, ProjectInviteUserNotification::class);
    }

    /** @test */
    public function a_user_cannot_invite_another_user_to_a_project_does_not_own()
    {
        $project = ProjectFactory::create();

        $me = $this->signIn();

        $taylor = factory(User::class)->create();

        $this->post('/projects/invitation/'. $project->id, ['email' =>  $taylor->email])->assertStatus(403);
    }

    /** @test */
    public function a_user_cannot_invite_himself_to_a_project()
    {
        $me = $this->signIn();

        $project = ProjectFactory::ownedBy($me)->create();

        $response = $this->post('/projects/invitation/'. $project->id, ['email' => $me->email]);

        $response->assertSessionHasErrors('email', null, 'invitation');
    }

    /** @test */
    public function it_require_valide_email()
    {
        $me = $this->signIn();
        $project = ProjectFactory::ownedBy($me)->create();

        $response = $this->post('/projects/invitation/'. $project->id, ['email' => 'test']);

        $response->assertSessionHasErrors('email', null,'invitation');
    }

    /** @test */
    public function invited_user_cannot_delete_a_project()
    {
        $project  = ProjectFactory::ownedBy($me = $this->signIn())->create();

        $taylor = factory(User::class)->create();

        $this->post('/projects/invitation/'. $project->id, ['email' => $taylor->email]);

        $this->signIn($taylor);

        $this->delete('/projects/'. $project->id)->assertStatus(403);

        $this->get('/projects/')->assertDontSee('delete');
    }
}
