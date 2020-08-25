@if ($errors->invitation->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->invitation->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
