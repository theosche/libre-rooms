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
        SystemSettings::create([
            'mail_host' => config('seeding.mail_host'),
            'mail_port' => config('seeding.mail_port'),
            'mail' => config('seeding.mail'),
            'mail_pass' => config('seeding.mail_pass'),
            'dav_url' => config('seeding.dav_url'),
            'dav_user' => config('seeding.dav_user'),
            'dav_pass' => config('seeding.dav_pass'),
            'webdav_user' => config('seeding.webdav_user'),
            'webdav_pass' => config('seeding.webdav_pass'),
            'webdav_endpoint' => config('seeding.webdav_endpoint'),
            'webdav_save_path' => config('seeding.webdav_save_path'),
            'currency' => 'CHF',
            'locale' => 'fr-CH',
        ]);
    }
}
