<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FreeTestResultMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declare the properties
    public $user;
    public $quiz;

    // Constructor to pass user and plan name
    public function __construct($user, $quiz)
    {
        $this->user = $user;
        $this->quiz = $quiz;
    }

    // Build the message
    public function build()
    {
        return $this->view('front.emails.free_test_result')
                    ->subject('Thank you for completing your free nutrition test!')
                    ->with([
                        'user' => $this->user,
                        'quiz' => $this->quiz
                    ]);
    }
}
