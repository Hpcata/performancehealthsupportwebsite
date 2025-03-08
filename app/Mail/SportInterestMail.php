<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\SportTracking;

class SportInterestMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declare the properties
    public $interest;

    public function __construct(SportTracking $interest)
    {
        $this->interest = $interest;
    }

    public function build()
    {
        return $this->subject('Nutrition Information for ' . $this->interest->sport_game)
                    ->view('front.emails.sport_interest')
                    ->with([
                        'interest' => $this->interest,
                    ]);
    }
    // Build the message
    // public function build()
    // {
    //     return $this->view('front.emails.free_test_result')
    //                 ->subject('Thank you for submitting your free test results!')
    //                 ->with([
    //                     'user' => $this->interest,
    //                 ]);
    // }
}
