<?php

namespace Tests\Importers;

use App\Import;
use App\Importers\PnpImporter;
use App\Repositories\CsvRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PnpImporterTest extends TestCase
{
    use DatabaseMigrations;

    public function testId() {
        $data = $this->fakeData(['Inventární číslo:' => 'A B/C']);
        $items = $this->importSingle($data);
        $this->assertEquals('CZE:PNP-A_B-C', $items[0]->id);
    }

    public function testTitle() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Název:'], $items[0]->title);
    }

    public function testAuthor() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Autor:'], $items[0]->author);
    }

    public function testDating() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Datace:'], $items[0]->dating);
    }

    public function testDateEarliest() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Rok - od'], $items[0]->date_earliest);
    }

    public function testDateLatest() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Rok - do'], $items[0]->date_latest);
    }

    public function testMeasurement() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Rozměry:'], $items[0]->measurement);
    }

    public function testWorkType() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Výtvarný druh:'], $items[0]->work_type);
    }

    public function testTopic() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Námět:'], $items[0]->topic);
    }

    public function testMedium() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Materiál:'], $items[0]->medium);
    }

    public function testTechnique() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Technika:'], $items[0]->technique);
    }

    public function testIdentifier() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Inventární číslo:'], $items[0]->identifier);
    }

    public function testInscription() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Značení:'], $items[0]->inscription);
    }

    public function testDescription() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data[' Popis:'], $items[0]->description);
    }

    protected function importSingle(array $data) {
        $records = new \ArrayIterator([$data]);
        $repositoryMock = $this->getMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $importer = new PnpImporter($repositoryMock);
        $import = Import::create();
        $file = ['basename' => '', 'path' => ''];
        $items = $importer->import($import, $file);

        $this->assertEquals('', $import->records()->first()->error_message);
        $this->assertCount(1, $items);

        return $items;
    }

    protected function fakeData(array $data = []) {
        return $data + [
            'Název:' => $this->faker->word,
            'Autor:' => $this->faker->name,
            'Datace:' => $this->faker->word,
            'Rok - od' => $this->faker->year,
            'Rok - do' => $this->faker->year,
            'Rozměry:' => $this->faker->word,
            'Výtvarný druh:' => $this->faker->word,
            'Námět:' => $this->faker->word,
            'Materiál:' => $this->faker->word,
            'Technika:' => $this->faker->word,
            'Inventární číslo:' => $this->faker->word,
            'Značení:' => $this->faker->word,
            ' Popis:' => $this->faker->sentence,
        ];
    }
}