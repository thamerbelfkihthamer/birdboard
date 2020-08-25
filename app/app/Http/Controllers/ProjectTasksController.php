<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\Validator;
use App\Task;

class ProjectTasksController extends Controller
{
	public function store(Project $project)
	{
        $this->authorize('update', $project);

		$validator = Validator::make(['body' => request('body')], [
			'body' => 'required'
		]);

		if ($validator->fails()) {
            return redirect(route('projects.show', $project->id))
                        ->withErrors($validator)
                        ->withInput();
        }


		$project->addTask(request('body'));

		return redirect($project->path());
	}

	public function update(Task $task)
	{
		$this->authorize('update', $task);

		$validator = Validator::make(['body' => request('body')], [
			'body' => 'required'
		]);

		if ($validator->fails()) {
            return redirect(route('projects.show', $task->project->id))
                        ->withErrors($validator)
                        ->withInput();
        }

        $task->update(['body' => request('body')]);

        if(request('completed')){
        	$task->complete();
        }else{
        	$task->incomplete();
        }

		return redirect($task->project->path());
	}

	public function delete(Task $task)
	{
		$this->authorize('delete', $task);

		$task->delete();

		return redirect($task->project->path());
	}
}
