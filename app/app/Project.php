<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Task;
use App\Activity;


class Project extends Model
{
	use SoftDeletes, ActivityRecord;

	protected $fillable = ['title', 'description', 'notes'];

	protected $casts = [
		'owner_id' => 'int'
	];

	public static  $events = ['created', 'updated'];

	public function path()
	{
		return "/projects/{$this->id}";
	}

	public function owner()
	{
		return $this->belongsTo(User::class);
	}

	public function tasks()
	{
		return $this->hasMany(Task::class);
	}

	public function addTask(String $body)
	{
		return $this->tasks()->create(compact('body'));
	}

	public function activities()
	{
		return $this->hasMany(Activity::class)->latest();
	}

	public function invite(User $user)
    {
        $this->members()->attach($user);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_members', 'project_id', 'member_id');
    }
}
