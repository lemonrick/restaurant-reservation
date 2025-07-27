<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@example.com',
            'phone' => '+421910645309',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::factory(10)->create();
    }
}
