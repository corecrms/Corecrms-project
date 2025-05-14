<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\SendPurchaseInvoicePaymentEmail;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPurchaseInvoicePayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $purchase,public $vendor, public $paymentInvoice,public $logo = null, public $totalDue = 0.00)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->vendor)->send(new SendPurchaseInvoicePaymentEmail($this->purchase,$this->paymentInvoice, $this->logo, $this->totalDue));

    }
}
