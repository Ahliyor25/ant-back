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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("description");
            $table->string("image")->nullable();
            $table->date('published_at')->nullable();
            $table->enum('type', ['news', 'promotion'])->default('promotion');
            $table->string('slug')->unique()->nullable();
            $table->tinyInteger('order')
            ->default(0)
            ->nullable();
        $table->foreignIdFor(\App\Models\Language::class)
            ->references('id')
            ->on('languages')
            ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion');
    }
};
