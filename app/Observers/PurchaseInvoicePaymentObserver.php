<?php

namespace App\Observers;

use App\Models\PurchaseInvoicePayment;
use App\Models\Account;

class PurchaseInvoicePaymentObserver
{
    /**
     * Handle the PurchaseInvoicePayment "created" event.
     *
     * @param  \App\Models\PurchaseInvoicePayment  $PurchaseInvoicePayment
     * @return void
     */
    public function created(PurchaseInvoicePayment $PurchaseInvoicePayment)
    {
        // Account::find($PurchaseInvoicePayment->from_account_id)->decrement('init_balance', $PurchaseInvoicePayment->amount);
        // Account::find($PurchaseInvoicePayment->to_account_id)->increment('init_balance', $PurchaseInvoicePayment->amount);
    }

    /**
     * Handle the PurchaseInvoicePayment "updated" event.
     *
     * @param  \App\Models\PurchaseInvoicePayment  $PurchaseInvoicePayment
     * @return void
     */
    public function updated(PurchaseInvoicePayment $PurchaseInvoicePayment)
    {
        // Get the original data before the update
        $original = $PurchaseInvoicePayment->getOriginal();

        Account::find($original['from_account_id'])->increment('init_balance', $original['amount']);
        Account::find($original['to_account_id'])->decrement('init_balance', $original['amount']);

        Account::find($PurchaseInvoicePayment->from_account_id)->decrement('init_balance', $PurchaseInvoicePayment->amount);
        Account::find($PurchaseInvoicePayment->to_account_id)->increment('init_balance', $PurchaseInvoicePayment->amount);
    }

    /**
     * Handle the PurchaseInvoicePayment "deleted" event.
     *
     * @param  \App\Models\PurchaseInvoicePayment  $PurchaseInvoicePayment
     * @return void
     */
    public function deleted(PurchaseInvoicePayment $PurchaseInvoicePayment)
    {
        Account::find($PurchaseInvoicePayment->from_account_id)->increment('init_balance', $PurchaseInvoicePayment->amount);
        Account::find($PurchaseInvoicePayment->to_account_id)->decrement('init_balance', $PurchaseInvoicePayment->amount);
    }

    /**
     * Handle the PurchaseInvoicePayment "restored" event.
     *
     * @param  \App\Models\PurchaseInvoicePayment  $PurchaseInvoicePayment
     * @return void
     */
    public function restored(PurchaseInvoicePayment $PurchaseInvoicePayment)
    {
        //
    }

    /**
     * Handle the PurchaseInvoicePayment "force deleted" event.
     *
     * @param  \App\Models\PurchaseInvoicePayment  $PurchaseInvoicePayment
     * @return void
     */
    public function forceDeleted(PurchaseInvoicePayment $PurchaseInvoicePayment)
    {
        //
    }
}
