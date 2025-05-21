<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::updateOrCreate(
            ['code' => 'ru'],
            [
                'name'       => 'Русский',
                'is_default' => true,
            ]
        );

        Language::updateOrCreate(
            ['code' => 'tj'],
            [
                'name'       => 'Тоҷикӣ',
                'is_default' => false,
            ]
        );
    }
}
