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
        Schema::table('configured_routers', function (Blueprint $table) {
            $table->foreignId('configured_by')->after('mac_batch')->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configured_routers', function (Blueprint $table) {
            $table->dropForeign(['configured_by']);
            $table->dropColumn('configured_by');
        });
    }
};
