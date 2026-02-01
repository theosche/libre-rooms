<?php

/**
 * Seeding configuration - values used by seeders and factories.
 * These are loaded from .env and are not committed to the repository.
 */

return [
    'mail_host' => env('FACTORY_MAIL_HOST'),
    'mail_port' => env('FACTORY_MAIL_PORT'),
    'mail' => env('FACTORY_MAIL'),
    'mail_pass' => env('FACTORY_MAIL_PASS'),

    'dav_url' => env('FACTORY_DAV_URL'),
    'dav_user' => env('FACTORY_DAV_USER'),
    'dav_pass' => env('FACTORY_DAV_PASS'),

    'webdav_endpoint' => env('FACTORY_WEBDAV_ENDPOINT'),
    'webdav_user' => env('FACTORY_WEBDAV_USER'),
    'webdav_pass' => env('FACTORY_WEBDAV_PASS'),
    'webdav_save_path' => env('FACTORY_WEBDAV_SAVE_PATH'),

    'idp_name' => env('FACTORY_IDP_NAME'),
    'idp_driver' => env('FACTORY_IDP_DRIVER'),
    'idp_issuer_url' => env('FACTORY_IDP_ISSUER_URL'),
    'idp_client_id' => env('FACTORY_IDP_CLIENT_ID'),
    'idp_client_secret' => env('FACTORY_IDP_CLIENT_SECRET'),
];
