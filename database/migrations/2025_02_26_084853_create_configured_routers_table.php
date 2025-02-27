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
        Schema::create('configured_routers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('router_model');
            $table->foreign('router_model')->references('id')->on('router_models')->onDelete('cascade');
            $table->string('serial_number');
            $table->string('mac_batch');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configured_routers');
    }
};
