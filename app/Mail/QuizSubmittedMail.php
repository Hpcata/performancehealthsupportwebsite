<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizSubmittedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $questionnaire;

    public function __construct($user, $questionnaire)
    {
        $this->user = $user;
        $this->questionnaire = $questionnaire;
    }

    public function build()
    {
        return $this->subject('New Quiz Submission')
                    ->view('front.emails.quiz_submitted');
    }
}
