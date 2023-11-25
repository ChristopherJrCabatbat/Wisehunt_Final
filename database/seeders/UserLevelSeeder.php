<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserLevel;

class UserLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLevel::create([
            'name' => 'Admin Name',
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        UserLevel::create([
            'name' => 'Staff Name',
            'username' => 'staff',
            'password' => Hash::make('staff'),
            'role' => 'staff',
        ]);
    }
}
