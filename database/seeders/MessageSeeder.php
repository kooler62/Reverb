<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        Message::factory(50)->create([
            'sender_id' => fn () => User::all()->random()->id,
            'receiver_id' => fn () => User::all()->random()->id,
        ]);
    }
}
