<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->create([
            'name' => 'Admin',
            'email' => 'admin@ant.tj',
            'phone' => null,
            'address' => null,
            'password' => Hash::make('YmSfaycmKjMt'),
        ]);
        $adminId = Role::query()->where('key', 'admin')->first();
        $managerId = Role::query()->where('key', 'manager')->first();
        $userId = Role::query()->where('key', 'user')->first();
        $user->roles()->sync([$adminId->id, $managerId->id, $userId->id]);
    }
}
