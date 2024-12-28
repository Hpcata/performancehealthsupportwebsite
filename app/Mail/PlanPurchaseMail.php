<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PlanPurchaseMail extends Mailable
{
    use Queueable, SerializesModels;

    // Declare the properties
    public $user;
    public $planName;

    // Constructor to pass user and plan name
    public function __construct($user, $planName)
    {
        $this->user = $user;
        $this->planName = $planName;
    }

    // Build the message
    public function build()
    {
        return $this->view('front.emails.plan_purchase')
                    ->subject('Thank you for purchasing the ' . $this->planName . ' plan!')
                    ->with([
                        'user' => $this->user,
                        'planName' => $this->planName
                    ]);
    }
}
