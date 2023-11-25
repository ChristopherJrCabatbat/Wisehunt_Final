<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\UserLevel;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserLevel::create([
            'name' => 'Admin Name',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
