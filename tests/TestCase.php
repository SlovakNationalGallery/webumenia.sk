<?php

namespace Tests;

use App\Authority;
use App\Item;
use App\ItemImage;
use App\SpiceHarvesterHarvest;
use App\SpiceHarvesterRecord;
use Elasticsearch\Client;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /** @var \Faker\Generator */
    protected $faker;

    /** @var array */
    protected $defaultModelData;

    public function setUp() {
        parent::setUp();

        $this->app->instance(Client::class, $this->getMock(Client::class));

        if ($this->faker === null) {
            $this->faker = \Faker\Factory::create(\App::getLocale());
        }

        $this->defaultModelData = [
            Authority::class => function () {
                return [
                    'id' => $this->faker->unique()->word,
                    'type' => $this->faker->word,
                    'type_organization' => $this->faker->word,
                    'name' => $this->faker->name,
                    'sex' => $this->faker->word,
                    'biography' => $this->faker->text,
                    'birth_place' => $this->faker->word,
                    'birth_date' => $this->faker->year,
                    'death_place' => $this->faker->word,
                    'death_date' => $this->faker->year,
                    'birth_year' => $this->faker->year,
                    'death_year' => $this->faker->year,
                    'has_image' => $this->faker->boolean,
                    'view_count' => $this->faker->randomNumber,
                    'image_source_url' => $this->faker->url,
                    'image_source_label' => $this->faker->word,
                ];
            },
            SpiceHarvesterHarvest::class => function () {
                return [
                    'type' => $this->faker->randomElement(['item', 'authority']),
                    'base_url' => $this->faker->url,
                    'metadata_prefix' => $this->faker->word,
                    'set_spec' => $this->faker->word,
                    'set_name' => $this->faker->word,
                    'set_description' => $this->faker->word,
                    'status_messages' => $this->faker->sentence,
                    'initiated' => $this->faker->date,
                ];
            },
            SpiceHarvesterRecord::class => function() {
                return [
                    'type' => $this->faker->randomElement(['item', 'authority']),
                    'identifier' => $this->faker->word,
                    'item_id' => $this->faker->word,
                    'datestamp' => $this->faker->date,
                ];
            },
            Item::class => function () {
                return [
                    'id' => $this->faker->unique()->word,
                    'work_type' => $this->faker->word,
                    'identifier' => $this->faker->word,
                    'title' => $this->faker->word,
                    'author' => $this->faker->name,
                    'topic' => $this->faker->word,
                    'place' => $this->faker->word,
                    'date_earliest' => $this->faker->year,
                    'date_latest' => $this->faker->year,
                    'medium' => $this->faker->word,
                    'technique' => $this->faker->word,
                    'gallery' => $this->faker->word,
                    'description' => $this->faker->word,
                    'work_level' => $this->faker->word,
                    'subject' => $this->faker->word,
                    'measurement' => $this->faker->word,
                    'item_type' => $this->faker->word,
                    'featured' => $this->faker->boolean,
                    'inscription' => $this->faker->word,
                ];
            },
            ItemImage::class => function () {
                return [
                    'iipimg_url' => $this->faker->url,
                ];
            },
        ];
    }

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->loadEnvironmentFrom('.env.testing');
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $this->baseUrl = env('TEST_HOST', 'http://localhost');

        return $app;
    }

    /**
     * @param string $type
     * @param string|null $message
     * @param callable $function
     */
    protected function assertException($type, $message, callable $function)
    {
        $exception = null;

        try {
            call_user_func($function);
        } catch (\Exception $e) {
            $exception = $e;
        }

        self::assertThat($exception, new \PHPUnit_Framework_Constraint_Exception($type));

        if ($message !== null) {
            self::assertThat($exception, new \PHPUnit_Framework_Constraint_ExceptionMessage($message));
        }
    }


    /**
     * @param string $modelClass
     * @param array $defaultData
     * @return Model
     */
    protected function createModel($modelClass, array $data = [], $save = true)
    {
        $model = new $modelClass();

        if (isset($this->defaultModelData[$modelClass])) {
            if (is_callable($this->defaultModelData[$modelClass])) {
                $data += $this->defaultModelData[$modelClass]();
            } else {
                $data += $this->defaultModelData[$modelClass];
            }
        }

        $model->forceFill($data);

        if ($save) {
            $model->save();
        }

        return $model;
    }
}
