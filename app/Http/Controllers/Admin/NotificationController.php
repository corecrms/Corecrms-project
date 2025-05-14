<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Jobs\SendNotiJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\UserNotification;
use App\Models\UserNotification as UserNotificationModel;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = UserNotificationModel::all();

        return view('back.cms.notification', compact('notifications'));
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
        $content = $request->message;
        $subject = $request->subject;

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', 'Client');
        })->get();

        UserNotificationModel::create([
            'subject' => $request->subject,
            'message' => $content
        ]);

        foreach ($users as $user) {
            // Dispatch the job
            SendNotiJob::dispatch($user->email, $subject, $content);
        }
        return redirect()->back()->with('success', 'Notification sent successfully');
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
        // delete all notification
        $notification = UserNotificationModel::findOrFail($id);
        $notification->delete();
        return redirect()->back()->with('success', 'Notification deleted successfully');
    }

    public function showOnFrontPage()
    {
        $notifications = UserNotificationModel::all();

        return view('user.notification.index', compact('notifications'));
    }
}
