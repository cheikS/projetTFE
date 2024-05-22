<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Video;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Video::create([
            'title' => 'Video One',
            'description' => 'Description for Video One',
            'url' => 'http://example.com/video1',
            'course_id' => 1,
        ]);

        Video::create([
            'title' => 'Video Two',
            'description' => 'Description for Video Two',
            'url' => 'http://example.com/video2',
            'course_id' => 1,
        ]);

        Video::create([
            'title' => 'Video Three',
            'description' => 'Description for Video Three',
            'url' => 'http://example.com/video3',
            'course_id' => 2,
        ]);

        Video::create([
            'title' => 'Video Four',
            'description' => 'Description for Video Four',
            'url' => 'http://example.com/video4',
            'course_id' => 2,
        ]);

        Video::create([
            'title' => 'Video Five',
            'description' => 'Description for Video Five',
            'url' => 'http://example.com/video5',
            'course_id' => 3,
        ]);
    }
}
