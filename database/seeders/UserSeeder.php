<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    protected const DEMO_USER_EMAIL = 'test@example.com';

    protected const DEMO_USER_1_EMAIL = 'test1@example.com';

    public function run(): void
    {
        $demoUserExists = User::query()->where('email', self::DEMO_USER_EMAIL)->exists();
        $demoUser1Exists = User::query()->where('email', self::DEMO_USER_1_EMAIL)->exists();

        if (! $demoUser1Exists) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => self::DEMO_USER_EMAIL,
            ]);
        }

        if (! $demoUser1Exists) {
            User::factory()->create([
                'name' => 'Test User 1',
                'email' => self::DEMO_USER_1_EMAIL,
            ]);
        }

        // User::factory(10)->create();

    }
}
