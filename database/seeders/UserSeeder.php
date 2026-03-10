<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    protected const DEMO_USER_EMAIL = 'test@example.com';

    public function run(): void
    {
        $demoUserExists = User::query()->where('email', self::DEMO_USER_EMAIL)->exists();

        if (!$demoUserExists) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => self::DEMO_USER_EMAIL,
            ]);
        }

        User::factory(10)->create();

    }
}
