<?php

namespace Database\Seeders;

use App\Technique;
use Illuminate\Database\Seeder;

class TechniqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $techniques = [
            [
                'name:sk' => 'akvarel',
                'name:cs' => 'akvarel',
                'name:en' => 'watercolor',
                'children' => [
                    [
                        'name:sk' => 'čierny',
                        'name:cs' => 'černý',
                        'name:en' => 'black',
                    ],
                ],
            ],
        ];

        foreach ($techniques as $technique) {
            Technique::create($technique);
        }
    }
}
