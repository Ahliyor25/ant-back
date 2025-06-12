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
        Schema::create('advertising_rates', function (Blueprint $table) {
            $table->id();
            $table->string('service_type')->nullable(); 
            $table->string('tariff_name')->nullable();
            $table->integer('discount_percent')->nullable();
            $table->string('duration')->nullable();
            $table->integer('show_count')->nullable();
            $table->decimal('price', 8, 2);
            $table->foreignIdFor(\App\Models\Language::class)
            ->references('id')
            ->on('languages')
            ->cascadeOnDelete();
            $table->string('show_period')->nullable();
            $table->integer('daily_shows')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advertising_rates');
    }
};
