<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use App\Project;
use App\Observers\ProjectObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('is_not_owner', function($attribute, $invitedEmail, $parameters, $validator){

            $user = User::where('email', $invitedEmail)->first();

            return auth()->user()->id !== $user->id;
        });

        Validator::extend('is_invited', function ($attribute, $invitedEmail, $paramters, $validator){

            $project = Project::findOrFail( (int) $paramters[0]);

            $user = User::where('email', $invitedEmail)->first();

            return ! $user->accessibleProjects()->contains($project);

        });

    }
}
