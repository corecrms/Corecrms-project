<?php

namespace App\Jobs;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendInvoiceSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $phone, public $sale)
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
        // $message = "Dear Customer, Your invoice has been generated successfully. Invoice No: " . $this->sale->invoice_number . " Amount: " . $this->sale->total_amount . " Thank you for your business.";
        // $this->sendSms($this->phone, $message);

        try {
            $sid = getenv("TWILIO_ACCOUNT_SID");
            $token = getenv("TWILIO_AUTH_TOKEN");
            $twilio = new Client($sid, $token);

            // SMS template
            $message = "Sale Invoice:\n\n" .
                "Dear Customer, Your invoice has been generated successfully.". "\n" .
                "Invoice No#: " . $this->sale->reference . "\n" .
                "Total: $" . number_format($this->sale->grand_total, 2) . "\n" .
                "Status: " . $this->sale->status . "\n\n" .
                "Company: " . getenv("COMPANY_NAME") . "\n\n" .
                "Thank you for your purchase!";

            // Send the SMS message
            $twilio->messages->create(
                // $this->phone,
                '+923164734175',
                [
                    "body" => $message,
                    "from" => getenv("TWILIO_PHONE"),
                ]
            );
            Log::info('SMS sent successfully');
        } catch (\Exception $e) {
            // dd($e->getMessage());
            // Handle the exception and redirect back with an error message
            Log::error('Failed to send SMS: ' . $e->getMessage());
        }
    }
}
