<?php

namespace App\Mail;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class SendInvoiceByEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(public $sale, public $logo = null, public $totalDue = 0.00)
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
        $pdf = Pdf::loadView('back.sales.invoice', ['sale' => $this->sale, 'logo' => $this->logo,'totalDue' => $this->totalDue]);


        // Attach the PDF to the email
        $this->attachData($pdf->output(), 'invoice.pdf', [
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
