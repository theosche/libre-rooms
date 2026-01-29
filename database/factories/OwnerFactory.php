<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Enums\InvoiceDueModes;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Owner>
 */
class OwnerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'contact_id' => Contact::factory(),
            'slug' => \Illuminate\Support\Str::slug($name),
            'invoice_due_mode' => fake()->randomElement([InvoiceDueModes::BEFORE_EVENT, InvoiceDueModes::AFTER_EVENT, InvoiceDueModes::AFTER_CONFIRM]),
            'invoice_due_days' => fake()->numberBetween(5, 30),
            'invoice_due_days_after_reminder' => fake()->numberBetween(7, 15),
            'max_nb_reminders' => fake()->numberBetween(1, 3),
            'mail_host' => env('FACTORY_MAIL_HOST'),
            'mail_port' => env('FACTORY_MAIL_PORT'),
            'mail' => env('FACTORY_MAIL'),
            'mail_pass' => env('FACTORY_MAIL_PASS'),
            'use_caldav' => fake()->boolean(),
            'dav_url' => env('FACTORY_DAV_URL'),
            'dav_user' => env('FACTORY_DAV_USER'),
            'dav_pass' => env('FACTORY_DAV_PASS'),
            'use_webdav' => fake()->boolean(),
            'webdav_user' => env('FACTORY_WEBDAV_USER'),
            'webdav_pass' => env('FACTORY_WEBDAV_PASS'),
            'webdav_endpoint' => env('FACTORY_WEBDAV_ENDPOINT'),
            'webdav_save_path' => env('FACTORY_WEBDAV_SAVE_PATH'),
        ];
    }
}
