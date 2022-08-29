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

$factory->define(\App\ItemImage::class, function (Faker\Generator $faker) {
    return [
        'iipimg_url' => join('/', ['', ...$faker->words(3)]),
    ];
});

$factory->define(\App\Authority::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->lexify,
        'type' => 'person',
        'type_organization' => $faker->word,
        'name' => $faker->name,
        'sex' => $faker->word,
        'biography' => $faker->text,
        'birth_place' => $faker->word,
        'birth_date' => $faker->year,
        'death_place' => $faker->word,
        'death_date' => $faker->year,
        'birth_year' => $faker->year,
        'death_year' => $faker->year,
        'has_image' => $faker->boolean,
        'view_count' => $faker->randomNumber,
        'image_source_url' => $faker->url,
        'image_source_label' => $faker->word,
        'created_at' => $faker->date,
        'updated_at' => $faker->date,
    ];
});

$factory->define(\App\SpiceHarvesterHarvest::class, function (Faker\Generator $faker) {
    return [
        'type' => 'item',
        'base_url' => $faker->url,
        'metadata_prefix' => $faker->word,
        'set_spec' => $faker->word,
        'set_name' => $faker->word,
        'set_description' => $faker->word,
        'status_messages' => $faker->sentence,
        'initiated' => $faker->date,
    ];
});

$factory->define(\App\SpiceHarvesterRecord::class, function (Faker\Generator $faker) {
    return [
        'type' => 'item',
        'identifier' => $faker->word,
        'item_id' => $faker->word,
        'datestamp' => $faker->date,
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

$factory->define(SharedUserCollection::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(Redirect::class, function (Faker\Generator $faker) {
    return [
        'source_url' => $faker->unique()->word,
        'target_url' => $faker->word,
        'is_enabled' => true,
    ];
});

$factory->define(ShuffledItem::class, function (Faker\Generator $faker) {
    return [
        'item_id' => factory(Item::class),
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
