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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'username' => $faker->userName,
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Role::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
    ];
});

$factory->define(\App\Item::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->randomNumber,
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
    ];
});

$factory->define(\App\ItemImage::class, function (Faker\Generator $faker) {
    return [
        'iipimg_url' => $faker->url,
    ];
});

$factory->define(\App\Authority::class, function (Faker\Generator $faker) {
    return [
        'id' => $faker->unique()->randomNumber,
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
    ];
});

$factory->define(\App\SpiceHarvesterHarvest::class, function (Faker\Generator $faker) {
    return [
        'type' => $faker->randomElement(['item', 'authority']),
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
        'type' => $faker->randomElement(['item', 'authority']),
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

$factory->define(App\Collection::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'type' => $faker->word,
        'text' => $faker->sentence,
        'order' => $faker->randomNumber,
    ];
});

$factory->define(App\Article::class, function (Faker\Generator $faker) {
    return [
        'author' => $faker->name,
        'slug' => $faker->unique()->word,
        'title' => $faker->word,
        'summary' => $faker->sentence,
        'content' => $faker->sentence,
        'main_image' => $faker->word,
        'title_color' => $faker->hexColor,
        'title_shadow' => $faker->hexColor,
        'promote' => $faker->boolean,
        'publish' => true,
    ];
});