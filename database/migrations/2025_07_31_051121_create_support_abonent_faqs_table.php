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
        Schema::create('support_abonent_faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer')->nullable();
            $table->foreignIdFor(\App\Models\Language::class)
                ->constrained('languages')
                ->cascadeOnDelete();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_abonent_faqs');
    }
};
