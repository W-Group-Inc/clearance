<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitResign extends Model
{
    //
    public static function statusTransitions()
    {
        return array(
            'Cleared' => array(
                'Ongoing Computation' => 'For Computation',
                'For Release' => 'For Release',
            ),
            'Ongoing Computation' => array(
                'For Release' => 'For Release',
            ),
            'For Release' => array(
                'Released' => 'Released',
            ),
        );
    }

    public function statusTransitionOptions()
    {
        $transitions = static::statusTransitions();

        return isset($transitions[$this->status]) ? $transitions[$this->status] : array();
    }

    public function allowedNextStatuses()
    {
        return array_keys($this->statusTransitionOptions());
    }

    public function Employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function Company()
    {
        return $this->belongsTo(Company::class);
    }
    public function Department()
    {
        return $this->belongsTo(Department::class);
    }
    public function exit_clearance()
    {
        return $this->hasMany(ExitClearance::class,'resign_id','id');
    }
    public function status_updates()
    {
        return $this->hasMany(ExitResignStatusUpdate::class, 'exit_resign_id')->orderBy('created_at', 'desc');
    }
}
