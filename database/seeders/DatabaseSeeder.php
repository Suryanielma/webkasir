<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'owner',
            'password' => '1234',
            'role' => 'Owner',
        ]);

        User::create([
            'username' => 'kasir',
            'password' => '1234',
            'role' => 'Kasir',
        ]);
    }
}
