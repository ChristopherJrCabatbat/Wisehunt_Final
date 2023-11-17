<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Login;
class Account extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'account' => 'admin',
                'username' => 'admin',
                'password' => 'admin',
                // 'password' => encrypt('Diana'),
            ]];
             foreach ($data as $record) {
            Login::create($record);
        }
    }
}
