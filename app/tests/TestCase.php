<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    protected function signIn($user = null)
    {
    	$user = $user ?? factory(User::class)->create();

    	$this->be($user);

    	return $user;
    }
}
