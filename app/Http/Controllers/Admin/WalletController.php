<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\SavedCreditCard;
use App\Models\SalesInvoicePayment;
use App\Http\Controllers\Controller;
use App\Models\CreditActivity;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function savedPaymentInfo()
    {
        $info = SavedCreditCard::where('user_id', auth()->id())->get();
        return view('user.wallet.saved-payment-info', compact('info'));
    }

    public function removeCard($id)
    {
        SavedCreditCard::find($id)->delete();
        return redirect()->back()->with('success', 'Card has been removed successfully!');
    }

    public function balanceSheet()
    {
        $salesInvoicePayments = SalesInvoicePayment::with(['salesPayment', 'saleInvoice'])
            ->whereHas('salesPayment', function ($query) {
                $query->where('customer_id', auth()->user()->customer->id);
            })
            ->get();
        // dd($salesInvoicePayments);
        return view('user.wallet.balance-sheet', compact('salesInvoicePayments'));
    }


    public function creditActivity(){
        $activities = CreditActivity::where('customer_id', auth()->user()->customer->id)->get();
        // dd($activities);
        return view('user.wallet.credit-activity', compact('activities'));
    }


    public function addCreditCard(Request $request){
        $request->validate([
            'card_brand' => 'required|string',
            'card_last_four' => 'required|numeric|digits:4',
            'card_exp_month' => 'required|numeric',
            'card_exp_year' => 'required|numeric',
        ]);

        $data = $request->all();
        // dd($data);
        auth()->user()->savedCards()->create($data);


        return redirect()->back()->with('success', 'Card has been added successfully!');
    }
}
