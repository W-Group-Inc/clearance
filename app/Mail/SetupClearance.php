<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetupClearance extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        //
        $this->request_data = $data;  
        
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Setup Clearance - '.$this->request_data['employee_info']->first_name.' '.$this->request_data['employee_info']->last_name)
        ->view('emails.setup_clearance')
        ->with([
            'data'  =>  $this->request_data
        ]);
    }
}
