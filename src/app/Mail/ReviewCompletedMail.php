<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $reviewer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($transaction, $reviewer)
    {
        $this->transaction = $transaction;
        $this->reviewer = $reviewer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('取引の評価が完了しました')
            ->view('emails.review_completed');
    }
}
