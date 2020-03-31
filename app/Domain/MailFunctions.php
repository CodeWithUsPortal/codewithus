<?php

namespace App\Domain;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;

class MailFunctions{
    public function send_free_session_successful_registration_email($to, $message)
	{
        $subject = "Free Session";
        Mail::to($to)->send(new SendMail($subject,$message));
        return back()->with('success','Email has been sent');
	}
}
