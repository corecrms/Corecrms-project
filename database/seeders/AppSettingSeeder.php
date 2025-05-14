<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            'APP_NAME' => 'Sales Purchase',
            // smtp
            'MAIL_HOST' => 'covi@digitalisolutions.net',
            'MAIL_PORT' => '465',
            'MAIL_USERNAME' => 'covi@digitalisolutions.net',
            'MAIL_PASSWORD' => 'LaravelPassword@123',
            'MAIL_ENCRYPTION' => 'ssl',
            'MAIL_FROM_ADDRESS' => 'covi@digitalisolutions.net',
            'MAIL_FROM_NAME' => 'Dine-Bot',
            // socialite
            'FACEBOOK_CLIENT_ID' => 'your_facebook_client_id',
            'FACEBOOK_CLIENT_SECRET' => 'your_facebook_client_secret',
            'FACEBOOK_REDIRECT' => 'your_facebook_redirect_url',

            'GOOGLE_CLIENT_ID' => 'your_google_client_id',
            'GOOGLE_CLIENT_SECRET' => 'your_google_client_secret',
            'GOOGLE_REDIRECT' => 'your_google_redirect_url',

            'STRIPE_KEY' => 'your_stripe_key',
            'STRIPE_SECRET' => 'your_stripe_secret',
        ];

        \App\Models\AppSetting::create($settings);
    }
}
