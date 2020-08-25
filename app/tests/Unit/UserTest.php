<?php

namespace Tests\Unit;

use App\Project;
use App\User;
use Facades\Tests\Setup\ProjectFactory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Collection;

class UserTest extends TestCase
{

	use RefreshDatabase;


	/** @test */
	public function a_user_has_projects()
	{
		$user = factory('App\User')->create();

		$this->assertInstanceOf(Collection::class, $user->projects);
	}

	/** @test */
	public function it_belongs_to_many_projects()
    {
        $user  = factory(User::class)->create();
        $this->assertInstanceOf(Collection::class, $user->projectsMember);
    }

    /** @test */
    public function it_has_accessible_projects()
    {
        $john = $this->signIn();

        $project = ProjectFactory::ownedBy($john)->create();


        $elissa = factory(User::class)->create();

        ProjectFactory::ownedBy($elissa)->create();

        $taylor = factory(User::class)->create();

        ProjectFactory::ownedBy($taylor)->create()->invite($john);

        $this->assertCount(2, $john->accessibleProjects());


    }

    /** */
    public function it_has_not_accessible_projects()
    {
        $this->assertCount(1, 1);
    }

    /** @test */
    public function it_has_avatar()
    {
        $user = factory(User::class)->create();

        $avatar = env('GRAVATAR_URL').md5($user->email).'?s=50';

        $this->assertSame($avatar, $user->avatar(50));
    }
}
