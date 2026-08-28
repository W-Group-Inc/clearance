<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExitResignStatusUpdate extends Model
{
    public function resign()
    {
        return $this->belongsTo(ExitResign::class, 'exit_resign_id');
    }

    public function updated_by_user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
