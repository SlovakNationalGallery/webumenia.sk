<?php

namespace Database\Seeders;

use App\WorkType;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workTypes = [
            [
                'name:sk' => 'fotografia',
                'name:cs' => 'fotografie',
                'name:en' => 'photograph',
                'children' => [
                    [
                        'name:sk' => 'negatív',
                        'name:cs' => 'negativ',
                        'name:en' => 'negative',
                    ],
                ],
            ],
        ];

        foreach ($workTypes as $workType) {
            WorkType::create($workType);
        }
    }
}
