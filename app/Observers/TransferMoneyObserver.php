<?php

namespace App\Observers;

use App\Models\TransferMoney;
use App\Models\Account;

class TransferMoneyObserver
{
    /**
     * Handle the TransferMoney "created" event.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return void
     */
    public function created(TransferMoney $transferMoney)
    {
        Account::find($transferMoney->from_account_id)->decrement('init_balance', $transferMoney->amount);
        Account::find($transferMoney->to_account_id)->increment('init_balance', $transferMoney->amount);
    }

    /**
     * Handle the TransferMoney "updated" event.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return void
     */
    public function updated(TransferMoney $transferMoney)
    {
        // Get the original data before the update
        $original = $transferMoney->getOriginal();

        Account::find($original['from_account_id'])->increment('init_balance', $original['amount']);
        Account::find($original['to_account_id'])->decrement('init_balance', $original['amount']);

        Account::find($transferMoney->from_account_id)->decrement('init_balance', $transferMoney->amount);
        Account::find($transferMoney->to_account_id)->increment('init_balance', $transferMoney->amount);
    }

    /**
     * Handle the TransferMoney "deleted" event.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return void
     */
    public function deleted(TransferMoney $transferMoney)
    {
        Account::find($transferMoney->from_account_id)->increment('init_balance', $transferMoney->amount);
        Account::find($transferMoney->to_account_id)->decrement('init_balance', $transferMoney->amount);
    }

    /**
     * Handle the TransferMoney "restored" event.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return void
     */
    public function restored(TransferMoney $transferMoney)
    {
        //
    }

    /**
     * Handle the TransferMoney "force deleted" event.
     *
     * @param  \App\Models\TransferMoney  $transferMoney
     * @return void
     */
    public function forceDeleted(TransferMoney $transferMoney)
    {
        //
    }
}
