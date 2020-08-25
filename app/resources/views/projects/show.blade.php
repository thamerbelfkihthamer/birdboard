@extends('layouts.app')

@section('content')

<br>
<br>
<br>
<div class="container">
    <div class="row">
        <div class="col-6 text-left">
            @include('layouts._invitation_errors')
            <form action="{{ route('project.invite', $project) }}" method="POST">
                @csrf
                <input name="email" class="form-control" />
                <br>
                <button class="btn btn-primary"> Send invitation</button>
            </form>
        </div>
        <div class="col-6">
            @forelse($project->members as $member)
                <img src="{{ $member->avatar(25) }}" alt="{{ $member->name }}" class="img-fluid float-right rounded" style="margin-left: 5px">
            @empty
            @endforelse
        </div>
    </div>
    <br>
	<div class="row">
		<div class="card col-md-8" style="width: 18rem;">
			    <div class="card-body">
			    <h6 class="card-subtitle mb-2 text-muted">
			    	<a href="{{ route('projects.show', $project->id) }}">
			    		{{ $project->title }}
			    	</a>
			    </h6>
			    <p class="card-text"> {{  $project->description }}</p>
			    <br>
			    <hr>
			    <h4>Tasks: </h4>
			    <br>
				    @forelse($project->tasks as $task)
					    <form action="{{ route('tasks.update', $task) }}" method="POST">
					    	@method('PATCH')
					    	@csrf
			         		<div class="form-group row">
			         			<div class="col-sm-10">
							    	<input  name="body" type="text" class="form-control {!! $task->completed ? 'is-valid' : ''  !!}" value="{{ $task->body }}">
			         			</div>
			         			<div class="col-sm-2">
					            	<input type="checkbox" name="completed" class="form-control" onchange="this.form.submit()" {{ $task->completed ? 'checked' : '' }} >
			         			</div>
			         		</div>
						</form>
				    @empty
				        no tasks yet.
				    @endforelse
			    <ul>
				    <br>
				    @include('layouts.error')
			    </ul>
			    <hr>
				    <form action="{{ route('tasks.store', $project->id) }}" method="POST">
				    	@csrf
					    <div class="form-group">
					        <input  name="body" type="text" class="form-control" placeholder="Add new Task">
					    </div>
					</form>
			    <br>
			    <hr>
			    <form action="{{ route('projects.update', $project) }}" method="POST">
			    	@method('PATCH')
			    	@csrf
			    	<textarea class="form-control" name="notes" placeholder="Add your notes ...">
			    		{{ $project->notes }}
			    	</textarea>
			    	<button class="btn btn-default"> Save notes</button>
			    </form>
			    <br>
			    <a href="{{ route('projects.index') }}" class="card-link btn btn-outline-info">back</a>
		    </div>
		</div>
		<div class="card col-md-4">
			<div class="card-body">
				@foreach($project->activities as $activity)
				    <h6 class="card-subtitle mb-2 text-muted">
				    		@include("activities.$activity->description"), {{ $activity->created_at->diffForHumans(null, true)}}
				    	</a>
				    </h6>
				@endforeach
			</div>
		</div>
	</div>
</div>
@endsection
