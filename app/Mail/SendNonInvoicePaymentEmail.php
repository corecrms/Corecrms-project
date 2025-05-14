<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNonInvoicePaymentEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $invoicePayments, public $logo = null, public $totalDue = 0.00)
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
        // Generate PDF from the invoice template
        $pdf = Pdf::loadView('back.non-sales-payments.non-invoice-payment', ['logo' => $this->logo, 'totalDue' => $this->totalDue, 'invoicePayments' => $this->invoicePayments]);


        // Attach the PDF to the email
        $this->attachData($pdf->output(), 'payment-invoice.pdf', [
            'mime' => 'application/pdf',
        ]);

        return $this->view('email.blank')
            ->subject('Invoice');
    }

    public function getLogo()
    {
        return Storage::url('public/' . $this->logo);
    }
}
