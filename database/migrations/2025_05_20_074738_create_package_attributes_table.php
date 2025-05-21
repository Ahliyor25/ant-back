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
        Schema::create('package_attributes', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\Package::class)
                ->references('id')
                ->on('packages')
                ->cascadeOnDelete();

            $table->string('icon')->nullable();
            $table->string('text')->nullable();
            $table->tinyInteger('order')->default(0);  // порядок отображения
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attributes_package');
    }
};
