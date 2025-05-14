<?php

namespace App\Jobs;

use App\Mail\SendTrackingNoViaEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendTrackingNoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $email, public $name, public $reference, public $trackingNo)
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
        // Send email to customer with tracking number
        // Log::info('Tracking number sent to ' . $this->email);
        Mail::to($this->email)->send(new SendTrackingNoViaEmail($this->name, $this->reference, $this->trackingNo));
    }
}
