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
        Schema::create('promotion_winners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Promotion::class)
            ->references('id')
            ->on('promotions')
            ->cascadeOnDelete();

            $table->foreignIdFor(\App\Models\PromotionDraw::class)
            ->references('id')
            ->on('promotion_draws')
            ->cascadeOnDelete()
            ->nullable();

            $table->foreignIdFor(\App\Models\PromotionPrize::class)
            ->references('id')
            ->on('promotion_prizes')
            ->cascadeOnDelete()
            ->nullable();
  
            $table->foreignIdFor(\App\Models\Region::class)
            ->references('id')
            ->on('regions')
            ->cascadeOnDelete();

            $table->string('full_name')->nullable();
            $table->string('month')->nullable();
            $table->string('prize_type')->nullable();
            $table->string('image')->nullable();
            
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_winners');
    }
};
