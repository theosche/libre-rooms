<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->string('street')->after('description');
            $table->string('postal_code', 25)->after('street');
            $table->string('city')->after('postal_code');
            $table->string('country', 100)->default('Suisse')->after('city');
            $table->decimal('latitude', 10, 8)->after('country');
            $table->decimal('longitude', 11, 8)->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropColumn(['street', 'postal_code', 'city', 'country', 'latitude', 'longitude']);
        });
    }
};
