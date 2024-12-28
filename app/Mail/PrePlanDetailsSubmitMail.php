<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PrePlanDetailsSubmitMail extends Mailable
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
       return $this->view('front.emails.pre_plan_submit')
                   ->subject('New Plan Purchased by ' . $this->user->name )
                   ->with([
                       'user' => $this->user,
                       'planName' => $this->planName
                   ]);
   }
}
