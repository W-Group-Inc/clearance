<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitResign extends Model
{
    //
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
}
