<?php

namespace Database\Seeders;

use App\Models\IdentityProvider;
use Illuminate\Database\Seeder;

class IdentityProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        IdentityProvider::create(
            [
                'name' => env('FACTORY_NAME'),
                'slug' => env('FACTORY_IDP_NAME'),
                'driver' => env('FACTORY_IDP_DRIVER'),
                'issuer_url' => env('FACTORY_IDP_ISSUER_URL'),
                'client_id' => env('FACTORY_IDP_CLIENT_ID'),
                'client_secret' => env('FACTORY_IDP_CLIENT_SECRET'),
                'enabled' => true,
            ]
        );
    }
}
