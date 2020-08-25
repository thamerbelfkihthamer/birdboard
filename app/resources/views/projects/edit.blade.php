@extends('layouts.app')
@section('content')
<div class="container">
	<dir class="row">
		<div class="col-sm">
			    <h1> update project</h1>
			    <hr>
			     @include('layouts.error')
			    <form action="{{ route('projects.update', $project) }}" method="post">
			    	@method('PATCH')
			    	@csrf
				    @include('projects.fields', ['buttonText' => 'edit project'])
				</form>
		</div>
		<div class="col-sm">
			
		</div>
	</dir>
</div>
@endsection
