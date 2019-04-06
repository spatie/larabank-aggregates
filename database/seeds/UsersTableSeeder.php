<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create([
            'name' => 'Freek',
            'email' => 'freek@spatie.be',
        ]);
    }
}
