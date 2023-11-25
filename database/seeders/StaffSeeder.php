<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserLevel;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLevel::create([
            'name' => 'Staff Name',
            'username' => 'staff',
            // 'password' => Hash::make('password'),
            'password' => 'staff',
            'role' => 'staff',
        ]);
    }
}
