<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Spatie\MediaLibrary\MediaCollections\Models\Media;

$factory->define(Media::class, function (Faker\Generator $faker) {
    return [
        'disk' => 'media',
        'name' => $faker->word,
        'file_name' => $faker->word,
        'size' => $faker->randomNumber,
        'manipulations' => [],
        'custom_properties' => [],
        'generated_conversions' => [],
        'responsive_images' => [],
    ];
});
