<?php

use App\Models\Reservation;
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
        Schema::table('reservations', function (Blueprint $table) {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('approve', 'pending', 'reject', 'closed', 'canceled') NOT NULL DEFAULT 'pending' AFTER item_id");

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            DB::statement("ALTER TABLE reservations MODIFY COLUMN status ENUM('approve', 'pending', 'reject', 'closed') NOT NULL DEFAULT 'pending' AFTER item_id");
        });
    }
};
