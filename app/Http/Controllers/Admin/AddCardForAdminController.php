<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AdminCreditCard;
use App\Http\Controllers\Controller;

class AddCardForAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cards = AdminCreditCard::all();
        return view('back.credit-card.index', compact('cards'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'card_brand' => 'required|string',
            'card_last_four' => 'required|numeric|digits:4',
            'card_exp_month' => 'required|numeric|digits:2',
            'card_exp_year' => 'required|numeric|digits:4|after:'.date('Y'),
        ],[
            'card_brand.required' => 'The card brand field is required.',
            'card_last_four.required' => 'The card last four field is required.',
            'card_last_four.numeric' => 'The card last four must be a number.',
            'card_last_four.digits' => 'The card last four must be 4 digits.',
            'card_exp_month.required' => 'The card expiry month field is required.',
            'card_exp_month.numeric' => 'The card expiry month must be a number.',
            'card_exp_month.digits' => 'The card expiry month must be 2 digits.',
            'card_exp_year.required' => 'The card expiry year field is required.',
            'card_exp_year.numeric' => 'The card expiry year must be a number.',
            'card_exp_year.digits' => 'The card expiry year must be 4 digits.',
        ]);

        $data = $request->all();

        $user = User::first();
        $user->adminCreditCards()->create($data);

        return redirect()->back()->with('success', 'Card has been added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'card_brand' => 'required|string',
            'card_last_four' => 'required|numeric|digits:4',
            'card_exp_month' => 'required|numeric|digits:2',
            'card_exp_year' => 'required|numeric|digits:4|after:'.date('Y'),
        ],[
            'card_brand.required' => 'The card brand field is required.',
            'card_last_four.required' => 'The card last four field is required.',
            'card_last_four.numeric' => 'The card last four must be a number.',
            'card_last_four.digits' => 'The card last four must be 4 digits.',
            'card_exp_month.required' => 'The card expiry month field is required.',
            'card_exp_month.numeric' => 'The card expiry month must be a number.',
            'card_exp_month.digits' => 'The card expiry month must be 2 digits.',
            'card_exp_year.required' => 'The card expiry year field is required.',
            'card_exp_year.numeric' => 'The card expiry year must be a number.',
            'card_exp_year.digits' => 'The card expiry year must be 4 digits.',
        ]);

        $data = $request->all();

        $card = AdminCreditCard::find($id);
        $card->update($data);

        return redirect()->back()->with('success', 'Card has been updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AdminCreditCard::find($id)->delete();
        return redirect()->back()->with('success', 'Card has been deleted successfully!');
    }
}
