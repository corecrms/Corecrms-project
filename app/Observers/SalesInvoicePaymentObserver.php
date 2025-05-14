<?php

namespace App\Observers;

use App\Models\SalesInvoicePayment;
use App\Models\Account;

class SalesInvoicePaymentObserver
{
    /**
     * Handle the SalesInvoicePayment "created" event.
     *
     * @param  \App\Models\SalesInvoicePayment  $SalesInvoicePayment
     * @return void
     */
    public function created(SalesInvoicePayment $SalesInvoicePayment)
    {
        // Account::find($SalesInvoicePayment->from_account_id)->decrement('init_balance', $SalesInvoicePayment->amount);
        // Account::find($SalesInvoicePayment->to_account_id)->increment('init_balance', $SalesInvoicePayment->amount);
    }

    /**
     * Handle the SalesInvoicePayment "updated" event.
     *
     * @param  \App\Models\SalesInvoicePayment  $SalesInvoicePayment
     * @return void
     */
    public function updated(SalesInvoicePayment $SalesInvoicePayment)
    {
        // Get the original data before the update
        $original = $SalesInvoicePayment->getOriginal();
        
        Account::find($original['from_account_id'])->increment('init_balance', $original['amount']);
        Account::find($original['to_account_id'])->decrement('init_balance', $original['amount']);

        Account::find($SalesInvoicePayment->from_account_id)->decrement('init_balance', $SalesInvoicePayment->amount);
        Account::find($SalesInvoicePayment->to_account_id)->increment('init_balance', $SalesInvoicePayment->amount);
    }

    /**
     * Handle the SalesInvoicePayment "deleted" event.
     *
     * @param  \App\Models\SalesInvoicePayment  $SalesInvoicePayment
     * @return void
     */
    public function deleted(SalesInvoicePayment $SalesInvoicePayment)
    {
        Account::find($SalesInvoicePayment->from_account_id)->increment('init_balance', $SalesInvoicePayment->amount);
        Account::find($SalesInvoicePayment->to_account_id)->decrement('init_balance', $SalesInvoicePayment->amount);
    }

    /**
     * Handle the SalesInvoicePayment "restored" event.
     *
     * @param  \App\Models\SalesInvoicePayment  $SalesInvoicePayment
     * @return void
     */
    public function restored(SalesInvoicePayment $SalesInvoicePayment)
    {
        //
    }

    /**
     * Handle the SalesInvoicePayment "force deleted" event.
     *
     * @param  \App\Models\SalesInvoicePayment  $SalesInvoicePayment
     * @return void
     */
    public function forceDeleted(SalesInvoicePayment $SalesInvoicePayment)
    {
        //
    }
}
