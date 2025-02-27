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
        // router models
        Schema::create('router_models', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model_name');
            $table->string('default_username')->nullable();
            $table->string('default_password')->nullable();
            $table->timestamps();
        });

        // issue types
        Schema::create('issue_types', function (Blueprint $table) {
            $table->id();
            $table->string('issue');
            $table->timestamps();
        });

        // router configurations
        Schema::create('router_configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('router_model_id');
            $table->foreign('router_model_id')->references('id')->on('router_models')->onDelete('cascade');
            $table->unsignedBigInteger('issue_id');
            $table->foreign('issue_id')->references('id')->on('issue_types')->onDelete('cascade');
            $table->text('configuration');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('router_configurations');
        Schema::dropIfExists('issue_types');
        Schema::dropIfExists('router_models');
    }
};