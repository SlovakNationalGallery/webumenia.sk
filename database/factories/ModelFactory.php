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

use App\Item;
use App\Redirect;
use App\SharedUserCollection;
use App\ShuffledItem;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

$factory->define(Item::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->lexify,
        'work_type' => $faker->word,
        'identifier' => $faker->word,
        'title' => $faker->word,
        'author' => $faker->name,
        'topic' => $faker->word,
        'place' => $faker->word,
        'date_earliest' => $faker->year,
        'date_latest' => $faker->year,
        'dating' => $faker->year,
        'medium' => $faker->word,
        'technique' => $faker->word,
        'gallery' => $faker->word,
        'description' => $faker->word,
        'work_level' => $faker->word,
        'subject' => $faker->word,
        'measurement' => $faker->word,
        'inscription' => $faker->word,
        'related_work_order' => $faker->randomNumber,
        'related_work_total' => $faker->randomNumber,
        'colors' => [
            $faker->hexColor => 1,
        ],
        'created_at' => $faker->date,
        'updated_at' => $faker->date,
    ];
});

$factory->define(\App\Link::class, function (Faker\Generator $faker) {
    return [
        'url' => $faker->url,
        'label' => $faker->sentence,
        'linkable_type' => $faker->word,
        'linkable_id' => $faker->randomNumber,
    ];
});

$factory->define(\App\Nationality::class, function (Faker\Generator $faker) {
    return [
        'code' => $faker->word,
    ];
});

$factory->define(\App\AuthorityRelationship::class, function (Faker\Generator $faker) {
    return [
        'authority_id' => $faker->randomNumber,
        'related_authority_id' => $faker->randomNumber,
        'type' => $faker->word,
    ];
});

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
