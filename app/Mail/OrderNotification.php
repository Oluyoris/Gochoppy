<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $message;
    public $user;

    public function __construct($title, $message, $user)
    {
        $this->title = $title;
        $this->message = $message;
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject($this->title)
                    ->view('emails.order-notification')
                    ->with([
                        'title' => $this->title,
                        'message' => $this->message,
                        'user' => $this->user,
                    ]);
    }
}