<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaymentInvoiceEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $sale,public $paymentInvoice ,public $logo = null, public $totalDue = 0.00)
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
        $pdf = Pdf::loadView('back.sales.payment-invoice', ['sale' => $this->sale, 'logo' => $this->logo, 'totalDue' => $this->totalDue, 'paymentInvoice' => $this->paymentInvoice]);


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
