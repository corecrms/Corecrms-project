<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class AppSettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:app-settings', ['only' => ['index', 'update']]);
    }

    public function index()
    {
        $settings = AppSetting::first();
        return view('app-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'APP_NAME' => 'required|string',
            'MAIL_HOST' => 'required|string',
            'MAIL_PORT' => 'required|numeric',
            'MAIL_USERNAME' => 'required|email',
            'MAIL_PASSWORD' => 'required|string',
            'MAIL_ENCRYPTION' => 'required|string',
            'MAIL_FROM_ADDRESS' => 'required|email',
            'MAIL_FROM_NAME' => 'required|string',
            'FACEBOOK_CLIENT_ID' => 'required|string',
            'FACEBOOK_CLIENT_SECRET' => 'required|string',
            'FACEBOOK_REDIRECT' => 'required|string',
            'GOOGLE_CLIENT_ID' => 'required|string',
            'GOOGLE_CLIENT_SECRET' => 'required|string',
            'GOOGLE_REDIRECT' => 'required|string',
            'STRIPE_KEY' => 'required|string',
            'STRIPE_SECRET' => 'required|string',
        ]);

        $settings = AppSetting::first();

        if ($settings) {
            $updated = $settings->update($data);
        } else {
            $updated = AppSetting::create($data);
        }

        // check if settings created successfully
        if (!$updated) {
            Session::flash('error', 'App settings failed to update!');
            return redirect()->back();
        }

        // check if .env file exists
        if (!file_exists(base_path('.env'))) {
            Session::flash('error', 'Please create .env file!');
            return redirect()->back();
        }

        // check if .env file is writable
        if (!is_writable(base_path('.env'))) {
            Session::flash('error', 'Please add write permission to .env file!');
            return redirect()->back();
        }

        if (!update_env($data)) {
            Session::flash('error', 'Settings Failed to Update .env file!');
            return redirect()->back();
        }

        // config
        Artisan::call('config:clear');

        return redirect()->route('app.settings.index')->with('success', 'App settings updated successfully.');
    }
}
