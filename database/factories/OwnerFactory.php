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
            'website' => fake()->url,
            'invoice_due_mode' => fake()->randomElement([InvoiceDueModes::BEFORE_EVENT, InvoiceDueModes::AFTER_EVENT, InvoiceDueModes::AFTER_CONFIRM]),
            'invoice_due_days' => fake()->numberBetween(5, 30),
            'invoice_due_days_after_reminder' => fake()->numberBetween(7, 15),
            'max_nb_reminders' => fake()->numberBetween(1, 3),
            'mail_host' => config('seeding.mail_host'),
            'mail_port' => config('seeding.mail_port'),
            'mail' => config('seeding.mail'),
            'mail_pass' => config('seeding.mail_pass'),
            'use_caldav' => fake()->boolean(),
            'dav_url' => config('seeding.dav_url'),
            'dav_user' => config('seeding.dav_user'),
            'dav_pass' => config('seeding.dav_pass'),
            'use_webdav' => fake()->boolean(),
            'webdav_user' => config('seeding.webdav_user'),
            'webdav_pass' => config('seeding.webdav_pass'),
            'webdav_endpoint' => config('seeding.webdav_endpoint'),
            'webdav_save_path' => config('seeding.webdav_save_path'),
        ];
    }
}
