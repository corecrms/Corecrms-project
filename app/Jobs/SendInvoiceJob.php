<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Mail\SendInvoiceByEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Support\Facades\Log;

class SendInvoiceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $sale ,public $customer, public $logo = null, public $totalDue = 0.00)
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
        // $mail = new SendInvoiceByEmail($this->sale, getLogo());
        //         Mail::to($this->customer)->send($mail);

        Mail::to($this->customer)->send(new SendInvoiceByEmail($this->sale, $this->logo, $this->totalDue));
    }
}
