<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Comment::create([
            'user_id' => 1,
            'video_id' => 1,
            'content' => 'Great video!',
        ]);

        Comment::create([
            'user_id' => 2,
            'video_id' => 1,
            'content' => 'Very informative.',
        ]);

        Comment::create([
            'user_id' => 3,
            'video_id' => 2,
            'content' => 'I learned a lot from this video.',
        ]);

        Comment::create([
            'user_id' => 4,
            'video_id' => 3,
            'content' => 'Awesome!',
        ]);

        Comment::create([
            'user_id' => 5,
            'video_id' => 4,
            'content' => 'Thanks for sharing.',
        ]);
    }
}
