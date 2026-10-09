<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ClearanceStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $statusUpdate;

    public function __construct($statusUpdate)
    {
        $this->statusUpdate = $statusUpdate;
    }

    public function build()
    {
        $employee = $this->statusUpdate->resign->employee;

        return $this->subject('Clearance Status Update - '.$this->statusUpdate->status)
            ->view('emails.clearance_status_updated')
            ->with([
                'employee' => $employee,
                'statusUpdate' => $this->statusUpdate,
            ])
            ->attach(storage_path('app/'.ltrim($this->statusUpdate->document, '/')), [
                'as' => $this->statusUpdate->original_name,
            ]);
    }
}
