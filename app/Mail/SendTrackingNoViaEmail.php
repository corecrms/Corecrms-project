<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTrackingNoViaEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $name, public $reference, public $trackingNo)
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->view('view.name');
        return $this->view('email.send-tracking-no')
                    ->subject('Your tracking number')
                    ->with([
                        'reference' => $this->reference,
                        'trackingNo' => $this->trackingNo,
                        'name' => $this->name
                    ]);
    }
}
