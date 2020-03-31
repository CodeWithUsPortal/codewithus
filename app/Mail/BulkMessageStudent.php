<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BulkMessageStudent extends Mailable
{
    use Queueable, SerializesModels;

    public $m;
    public $user;

    /**
     * Create a new message instance.
     *
     * @param $user
     * @param $message
     */
    public function __construct($user,$m)
    {
        $this->m = $m;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.bulk_message_student')->subject('Message from Admin');
    }
}
