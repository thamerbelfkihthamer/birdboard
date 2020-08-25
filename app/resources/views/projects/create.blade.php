@extends('layouts.app')

@section('content')
<div class="container">
	<dir class="row">
		<div class="col-sm">
			    <h1> create new project</h1>
			    <hr>
			    @include('layouts.error')
			    <form action="{{ route('projects.store') }}" method="post">
			    	@csrf
					@include('projects.fields', ['buttonText' => 'Create project'])
				</form>
		</div>
		<div class="col-sm">
			
		</div>
	</dir>
</div>
@endsection
