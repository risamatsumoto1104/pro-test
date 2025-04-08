<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SellerRatedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $item;
    public $otherUser;
    public $rating;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($item, $otherUser, $rating)
    {
        $this->item = $item;
        $this->otherUser = $otherUser;
        $this->rating = $rating;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.seller-rated');
    }
}
