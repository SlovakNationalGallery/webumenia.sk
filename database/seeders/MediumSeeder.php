<?php

namespace Database\Seeders;

use App\Medium;
use Illuminate\Database\Seeder;

class MediumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $media = [
            [
                'name:sk' => 'papier',
                'name:cs' => 'papír',
                'name:en' => 'paper',
                'children' => [
                    [
                        'name:sk' => 'matný',
                        'name:cs' => 'matný',
                        'name:en' => 'matte',
                    ],
                ],
            ],
        ];

        foreach ($media as $medium) {
            Medium::create($medium);
        }
    }
}
