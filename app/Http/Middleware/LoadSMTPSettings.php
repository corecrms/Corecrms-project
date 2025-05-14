<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class LoadSMTPSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $settings = DB::table('settings')->first();

        if ($settings && $settings->smtp_host && $settings->smtp_port && $settings->smtp_username && $settings->smtp_password && $settings->smtp_encryption && $settings->smtp_address && $settings->smtp_from_name) {
            Config::set('mail.mailers.smtp.host', $settings->smtp_host);
            Config::set('mail.mailers.smtp.port', $settings->smtp_port);
            Config::set('mail.mailers.smtp.username', $settings->smtp_username);
            Config::set('mail.mailers.smtp.password', $settings->smtp_password);
            Config::set('mail.mailers.smtp.encryption', $settings->smtp_encryption);
            Config::set('mail.from.address', $settings->smtp_address);
            Config::set('mail.from.name', $settings->smtp_from_name);
        }

        if ($settings && $settings->fedex_api_key && $settings->fedex_secret_key && $settings->fedex_account_number) {
            Config::set('fedex.client_key', $settings->fedex_api_key);
            Config::set('fedex.client_secret', $settings->fedex_secret_key);
            Config::set('fedex.account_number', $settings->fedex_account_number);
            Config::set('fedex.api_url', $settings->fedex_api_url);
        }

        // if($settings && $settings->paypal_client_id && $settings->paypal_client_secret && $settings->paypal_app_id){
        //     Config::set('paypal.client_id', $settings->paypal_client_id);
        //     Config::set('paypal.client_secret', $settings->paypal_client_secret);
        //     Config::set('paypal.app_id', $settings->paypal_app_id);
        // }

        // if($settings && $settings->stripe_key && $settings->stripe_secret){
        //     Config::set('stripe.key', $settings->stripe_key);
        //     Config::set('stripe.secret', $settings->stripe_secret);
        // }


        return $next($request);
    }
}
