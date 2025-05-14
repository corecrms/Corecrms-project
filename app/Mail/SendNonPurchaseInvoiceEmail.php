<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNonPurchaseInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $vendor, public $amount_pay, public $payment_date)
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
        return $this->view('email.non-purchase-invoice')
            ->subject('Non Purchase Invoice Payment')
            ->with([
                'name' => $this->vendor->user->name ?? 'Vendor',
                'amount_pay' => $this->amount_pay,
                'payment_date' => $this->payment_date,
            ]);
    }
}
