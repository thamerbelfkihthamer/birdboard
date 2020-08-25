<?php

namespace App\Http\Controllers;

use App\Notifications\ProjectInviteUserNotification;
use App\Project;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class ProjectInvitationsController extends Controller
{

    public function store(Project $project)
    {
        $this->authorize('update', $project);

        $this->validateWithBag('invitation', request(), [
            'email' => 'bail|required|email|exists:users,email|is_not_owner|is_invited:'.$project->id .''
        ]);

        $user = User::where('email', request('email'))->first();

        $project->invite($user);

        $user->notify(new ProjectInviteUserNotification($project));

        return redirect($project->path())->with('success', 'invitation envoyer avec succees');
    }

}
