<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mac_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('mac_address', 17)->unique(); // Store full MAC
            $table->boolean('assigned')->default(false); // Track if it's used
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mac_addresses');
    }
};
