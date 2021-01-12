<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        User::factory()->create([
            'name' => 'User',
            'email' => 'user@larabank.com',
        ]);

        User::factory()->create([
            'name' => 'freek',
            'email' => 'freek@spatie',
            'password' => bcrypt('password'),
        ]);
    }
}
