<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Quote;

class TestMotivationalEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $quote;

    public function __construct()
    {
        $this->quote = Quote::inRandomOrder()->first();
    }

    public function build()
    {
        return $this->view('emails.motivational')
                   ->subject('Your Daily Motivation from DailyDrive');
    }
}
