<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Services\SupportService;
use App\Http\Controllers\Controller;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(protected SupportService $supportService)
    {

    }
    public function index()
    {
        return view('user.support-ticket.index', [
            'tickets' => $this->supportService->ticketByUserId(auth()->id()),
        ]);
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
            'message' => 'required',
        ]);
        $data = $request->all();
        $data['ticket_id'] = rand(100000, 999999);
        if($request->hasFile('attachment')){
            $data['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }
        $data['customer_id'] = auth()->id();
        $this->supportService->create($data);
        return redirect()->route('support-ticket.index')->with('success', 'Support ticket has been created successfully');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
