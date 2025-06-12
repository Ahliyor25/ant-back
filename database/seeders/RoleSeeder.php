<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::query()->firstOrCreate([
            'key' => 'admin',
            'name' => 'Администратор',
        ]);
        Role::query()->firstOrCreate([
            'key' => 'manager',
            'name' => 'Менеджер',
        ]);
        Role::query()->firstOrCreate([
            'key' => 'user',
            'name' => 'Пользователь',
        ]);
    }
}
