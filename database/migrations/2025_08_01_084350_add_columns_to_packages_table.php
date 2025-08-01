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
        Schema::table('packages', function (Blueprint $table) {
            //
            $table->enum('type_connection', ['on_connect', 'on_use'])
                ->default('on_connect')
                ->after('yearly_price');
            $table->enum('type', ['standard', 'discount'])
                ->default('standard')
                ->after('type_connection');
            $table->decimal('discount', 5, 2)->default(0)->nullable()->after('type');
        });
    }

    /**ы
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            //
            $table->dropColumn(['type', 'discount']);
        });
    }
};
