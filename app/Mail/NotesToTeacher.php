<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotesToTeacher extends Mailable
{
    use Queueable, SerializesModels;

    public $notes;

    /**
     * Create a new message instance.
     *
     * @param $notes
     */
    public function __construct($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.notes-to-teacher');
    }
}
