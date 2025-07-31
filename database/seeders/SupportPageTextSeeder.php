<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupportPageTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        DB::table('support_page_texts')->truncate();

        DB::table('support_page_texts')->insert([
            [
                'language_id' => 1,
                'title' => 'Служба технической поддержки',
                'description' => 'Служба поддержки АНТ поможет вам решить любые задачи...',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'language_id' => 2,
                'title' => 'Technical Support Service',
                'description' => 'ANT technical support will help you solve any tasks...',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
