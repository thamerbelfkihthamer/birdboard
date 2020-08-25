Hello  Mr(e) <span class="font-bold">{{ $notifiable->name }},</span>
<br>
<br>
You have received a new invitation from Mr(e)  <span class="font-bold"> {{ $project->owner->name }} </span> to join his project

<a href="{{ route('projects.show', $project->id) }}" class="btn btn-primary"> {{ $project->title }}</a>
<br>
<br>
<br>
Regards, <br><br>
Birdboard Team<br>
