<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Project;
use Illuminate\Database\Eloquent\Collection;

class User extends Authenticatable
{
    use Notifiable;

    protected  $table  = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function projects()
    {
        return $this->hasMany(Project::class, 'owner_id')->latest('updated_at');
    }

    public function projectsMember()
    {
        return $this->belongsToMany(Project::class, 'project_members', 'member_id', 'project_id');
    }

    public function accessibleProjects()
    {
        // may be it's not good for perfermance

        return $this->projects->merge($this->projectsMember);
    }

    public function avatar(int $size = 50): string
    {
        return env('GRAVATAR_URL').md5($this->email).'?s='.$size;
    }

}
