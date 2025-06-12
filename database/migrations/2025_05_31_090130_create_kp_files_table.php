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
        Schema::create('kp_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()
            ->constrained()
            ->nullOnDelete();
            $table->string('title')->nullable();             
            $table->string('file'); 
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kp_files');
    }
};
