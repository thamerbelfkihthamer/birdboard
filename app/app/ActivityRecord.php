<?php


namespace App;


trait ActivityRecord
{
    public $oldAttributes = [];

    public static function bootActivityRecord()
    {
        $events = ['created', 'updated', 'deleted'];

        if(isset(static::$events)){
            $events = static::$events;
        }


        foreach (static::$events as $event){
            static::$event(function ($model) use ($event){
                $model->recordActivity($event);
            });

            if($event === 'updated')
            {
                static::updating(function($model){
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }



    public function recordActivity(string $description)
    {
        $this->activities()->create([
            'user_id' => ($this->project ?? $this)->owner->id,
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id ,
            'description' => $this->activityDescription($description),
            'changes' => $this->activityChanges()
        ]);
    }

    protected function activityDescription(string $description)
    {
        return $description.'_'. strtolower(class_basename($this));
    }


    protected function activityChanges()
    {
        if($this->wasChanged())
        {
            return  [
                'before' => array_except(array_diff($this->oldAttributes, $this->getAttributes()), 'updated_at'),
                'after' => array_except($this->getChanges(), 'updated_at'),
            ];
        }
    }

}
