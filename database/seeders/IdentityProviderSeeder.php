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
                'name' => config('seeding.idp_name'),
                'slug' => config('seeding.idp_name'),
                'driver' => config('seeding.idp_driver'),
                'issuer_url' => config('seeding.idp_issuer_url'),
                'client_id' => config('seeding.idp_client_id'),
                'client_secret' => config('seeding.idp_client_secret'),
                'enabled' => true,
            ]
        );
    }
}
