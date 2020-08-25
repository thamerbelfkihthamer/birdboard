<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Project;

class Task extends Model
{
    use ActivityRecord;

	protected $fillable  = ['body', 'completed'];

	protected $touches = ['project'];

	protected $casts =  [
		'completed' => 'boolean'
	];

    public static  $events = ['created', 'deleted'];

	public function complete()
	{
		$this->update(['completed' => true]);

		$this->recordActivity('completed');
	}

	public function incomplete()
	{
		$this->update(['completed' => false]);

		$this->recordActivity('incompleted');
	}


	public function project()
	{
		return $this->belongsTo(Project::class);
	}


	public function activities()
	{
		return $this->morphMany('App\Activity', 'subject');
	}
}
