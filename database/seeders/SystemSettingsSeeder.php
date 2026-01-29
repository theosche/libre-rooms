<?php

namespace Database\Seeders;

use App\Models\SystemSettings;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSettings::create(
            [
                'mail_host' => env('FACTORY_MAIL_HOST'),
                'mail_port' => env('FACTORY_MAIL_PORT'),
                'mail' => env('FACTORY_MAIL'),
                'mail_pass' => env('FACTORY_MAIL_PASS'),
                'dav_url' => env('FACTORY_DAV_URL'),
                'dav_user' => env('FACTORY_DAV_USER'),
                'dav_pass' => env('FACTORY_DAV_PASS'),
                'webdav_user' => env('FACTORY_WEBDAV_USER'),
                'webdav_pass' => env('FACTORY_WEBDAV_PASS'),
                'webdav_endpoint' => env('FACTORY_WEBDAV_ENDPOINT'),
                'webdav_save_path' => env('FACTORY_WEBDAV_SAVE_PATH'),
                'currency' => 'CHF',
                'locale' => 'fr-CH',
            ]
        );
    }
}
