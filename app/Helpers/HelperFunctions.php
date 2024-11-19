<?php

use App\ExitClearanceSignatory;
use App\ExitResign;
use App\Employee;
use Carbon\Carbon;


function for_clearance()
{
    $for_clearances = ExitClearanceSignatory::with('clearance')
    ->where('employee_id',auth()->user()->employee->id)
    ->where('status','Pending')
->count();

    return $for_clearances;
}
function for_setup()
{
    $exit = ExitResign::whereDoesntHave('exit_clearance')->count();

    return $exit;
}
function ongoing_clearance()
{
    $exit = ExitResign::where('status','Ongoing Clearance')->count();

    return $exit;
}
function cleared()
{
    $exit = ExitResign::where('status','Cleared')->count();

    return $exit;
}
function ongoing_computation()
{
    $exit = ExitResign::where('status','Ongoing Computation')->count();

    return $exit;
}
function for_release()
{
    $exit = ExitResign::where('status','For Release')->count();

    return $exit;
}

function get_avatar($id)
{
    $avatar = Employee::findOrfail($id);
    $image = "https://hris.wsystem.online/".$avatar->avatar;

    return $image;
}