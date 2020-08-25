
  <div class="form-group">
    <label for="title">Title</label>
    <input  name="title" type="text" class="form-control"  id="title" placeholder="Enter title" value=" @isset($project) {{ $project->title }} @endisset">
  </div>
  <div class="form-group">
	    <label for="description">Description</label>
	    <textarea  name="description" id="description"  rows="3" class="form-control">
	    	    @isset($project) {{ $project->description }} @endisset
	    </textarea>
  </div>

  <div class="form-group">
      <label for="notes">Notes</label>
      <textarea  name="notes" id="notes"  rows="3" class="form-control">
            @isset($project) {{ $project->notes }} @endisset
      </textarea>
  </div>
  <button type="submit" class="btn btn-primary">{{ $buttonText }}</button>