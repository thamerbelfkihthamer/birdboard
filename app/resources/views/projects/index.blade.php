@extends('layouts.app')

@section('content')
<br>
<div class="container">
	<div class="row">
		<div class="col-sm">

		</div>
		<div class="col-sm">

		</div>
		<div class="col-sm">
		  <a class="btn btn-outline-primary float-right" href="{{  route('projects.create')}}"> create new project </a>
		</div>
	</div>

	<br>
	<br>

	<div class="row">
		@forelse($projects as $project)
		<div class="card col-md-3" style="width: 18rem; margin: 1%">
			    <div class="card-body">
			    <h6 class="card-subtitle mb-2 text-muted">
			    	<a href="{{ route('projects.show', $project->id) }}">
			    		{{ str_limit($project->title, 50) }}
			    	</a>
			    </h6>
			    <p class="card-text"> {{ str_limit($project->description, 90) }}</p>
			    <br>
			    <a href="{{ route('projects.edit', $project->id) }}" class="card-link btn btn-outline-info">edit</a>
                    @can('delete', $project)
                        <form action="{{ route('projects.delete', $project) }}" method="POST" >
                            @method("DELETE")
                            @csrf
                            <button d class="btn btn-outline-danger"> delete</button>
                        </form>
                    @endcan
		    </div>
		</div>
  	@empty
  	    No projects yet
  	@endforelse
	</div>

</div>

@endsection
