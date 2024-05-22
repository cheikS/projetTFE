<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Message;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Message::create([
            'sender_id' => 1,
            'receiver_id' => 2,
            'content' => 'Hello from User One to User Two!',
        ]);

        Message::create([
            'sender_id' => 2,
            'receiver_id' => 1,
            'content' => 'Hello from User Two to User One!',
        ]);

        Message::create([
            'sender_id' => 3,
            'receiver_id' => 4,
            'content' => 'Hello from User Three to User Four!',
        ]);

        Message::create([
            'sender_id' => 4,
            'receiver_id' => 5,
            'content' => 'Hello from User Four to User Five!',
        ]);

        Message::create([
            'sender_id' => 5,
            'receiver_id' => 3,
            'content' => 'Hello from User Five to User Three!',
        ]);
    }
}
