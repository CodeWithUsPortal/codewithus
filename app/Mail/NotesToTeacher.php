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
    public $teacher;
    public $class;

    /**
     * Create a new message instance.
     *
     * @param $notes
     */
    public function __construct($notes, $teacher, $class)
    {
        $this->notes = $notes;
        $this->teacher = $teacher;
        $this->class = $class;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.notes_to_teacher')->subject('Notes for class '.$this->class->name);
    }
}
