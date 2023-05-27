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
        Schema::create('reservation_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservation_id')->unsigned();
            $table->integer('item_id')->unsigned();
            $table->integer('feature_item_id')->unsigned();
            $table->foreign('reservation_id')
                ->on('reservations')->references('id')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('item_id')->on('items')->references('id')->cascadeOnUpdate()->cascadeOnDelete();

            $table->foreign('feature_item_id')
                ->on('feature_items')->references('id')->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_items');
    }
};
