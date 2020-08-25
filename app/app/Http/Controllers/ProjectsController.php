<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Project;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use App\User;

class ProjectsController extends Controller
{

	public function index()
	{
        $projects = auth()->user()->accessibleProjects();

		return view('projects.index', compact('projects'));
	}

	public function show(Project $project)
	{
	    $this->authorize('view', $project);

		return view('projects.show', compact('project'));
	}

	public function create()
	{
		return view('projects.create');
	}

	public function store(Request $request)
	{

		$validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required',
            'notes' => 'max:255'
        ]);

         if ($validator->fails()) {
            return redirect(route('projects.create'))
                        ->withErrors($validator)
                        ->withInput();
        }

		$project = auth()->user()->projects()->create($request->only('title', 'description', 'notes'));

		return redirect($project->path());
	}

	public function edit(int $id)
	{
		$project = Project::findOrFail($id);

		$this->authorize('update', $project);

		return view('projects.edit', compact('project'));
	}

	public function update(Project $project)
	{
		$this->authorize('update', $project);

		$validator = Validator::make(request()->all(), [
            'title' => 'sometimes|required|max:255',
            'description' => 'sometimes|required',
            'notes' => 'max:255'
        ]);

        if ($validator->fails()) {
            return redirect(route('projects.edit', $project->id))
                        ->withErrors($validator)
                        ->withInput();
        }

		$project->update(request()->all());

		return redirect(route('projects.index'));
	}

	public function delete(Project $project)
	{
		$this->authorize('delete', $project);

		$project->delete();

		return redirect('/projects');
	}
}
