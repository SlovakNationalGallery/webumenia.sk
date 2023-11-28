<?php

namespace Database\Seeders;

use App\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $topics = [
            [
                'name:sk' => 'figurálna kompozícia',
                'name:cs' => 'figurální kompozice',
                'name:en' => 'figurative composition',
            ],
        ];

        foreach ($topics as $topic) {
            Topic::create($topic);
        }
    }
}
