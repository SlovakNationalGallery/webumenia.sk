<?php

namespace Tests\Importers;

use App\Import;
use App\Repositories\CsvRepository;
use App\Importers\WebumeniaMgImporter;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class WebumeniaMgImporterTest extends TestCase
{
    use DatabaseMigrations;

    public function testId() {
        $data = $this->fakeData([
            'Rada_S' => 'rada_s',
            'Lomeni_S' => 'lomeni_s',
            'PorC_S' => 123
        ]);
        $items = $this->importSingle($data);
        $this->assertEquals('CZE:MG.rada_s_123-lomeni_s', $items[0]->id);
    }

    public function testDating() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Datace'], $items[0]->dating);
    }

    public function testDateEarliest() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['RokOd'], $items[0]->date_earliest);
    }

    public function testDateLatest() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Do'], $items[0]->date_latest);
    }

    public function testPlace() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['MístoVz'], $items[0]->place);
    }

    public function testInscription() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Sign'], $items[0]->inscription);
    }

    public function testStateEdition() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Původnost'], $items[0]->state_edition);
    }

    public function testAuthor() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Autor'], $items[0]->author);
    }

    public function testTitle() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Titul'], $items[0]->title);
    }

    public function testTopic() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals($data['Námět'], $items[0]->topic);
    }

    public function testGallery() {
        $data = $this->fakeData();
        $items = $this->importSingle($data);
        $this->assertEquals('Moravská galerie, MG', $items[0]->gallery);
    }

    public function testIdentifier() {
        $data = $this->fakeData([
            'Rada_S' => 'rada_s',
            'Lomeni_S' => 'lomeni_s',
            'PorC_S' => 123
        ]);
        $items = $this->importSingle($data);
        $this->assertEquals('rada_s 123/lomeni_s', $items[0]->identifier);
    }

    public function testMedium() {
        $data = $this->fakeData([
            'Materiál' => 'material',
            'MatSpec' => 'matspec',
        ]);
        $items = $this->importSingle($data);
        $this->assertEquals('material, matspec', $items[0]->medium);
    }

    public function testTechnique() {
        $data = $this->fakeData([
            'Technika' => 'technika',
            'TechSpec' => 'techspec',
        ]);
        $items = $this->importSingle($data);
        $this->assertEquals('technika, techspec', $items[0]->technique);
    }

    public function testRelationshipType() {
        $data = $this->fakeData(['Okolnosti' => 'BB']);
        $items = $this->importSingle($data);
        $this->assertEquals('zo súboru', $items[0]->relationship_type);
    }

    public function testWorkType() {
        $data = $this->fakeData(['Skupina' => 'Ar']);
        $items = $this->importSingle($data);
        $this->assertEquals('architektúra', $items[0]->work_type);
    }

    public function testRelatedWork() {
        $data = $this->fakeData(['Okolnosti' => 'BB']);
        $items = $this->importSingle($data);
        $this->assertEquals('Bienále Brno', $items[0]->related_work);
    }

    public function testMeasurement() {
        $data = $this->fakeData(['Služ' => 's=4,6cm; d=6cm; a=2cm']);
        $items = $this->importSingle($data);
        $this->assertEquals('šírka 4,6 cm; dĺžka 6 cm; výška hlavnej časti 2 cm', $items[0]->measurement);
    }

    protected function importSingle(array $data) {
        $records = new \ArrayIterator([$data]);
        $repositoryMock = $this->getMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $importer = new WebumeniaMgImporter($repositoryMock);
        $import = Import::create();
        $file = ['basename' => '', 'path' => ''];
        $items = $importer->import($import, $file);

        $this->assertEquals('', $import->records()->first()->error_message);
        $this->assertCount(1, $items);

        return $items;
    }

    protected function fakeData(array $data = []) {
        $plus2TValidator = function ($value) {
            return $value !== 'ODPIS';
        };

        return $data + [
            'Datace' => $this->faker->sentence,
            'RokOd' => $this->faker->year,
            'Do' => $this->faker->year,
            'MístoVz' => $this->faker->city,
            'Sign' => $this->faker->sentence,
            'Původnost' => $this->faker->sentence,
            'Autor' => $this->faker->name,
            'Titul' => $this->faker->sentence,
            'Námět' => $this->faker->sentence,
            'Plus2T' => $this->faker->valid($plus2TValidator)->text,
            'Rada_S' => $this->faker->randomLetter,
            'PorC_S' => $this->faker->randomNumber,
            'Materiál' => $this->faker->sentence,
            'MatSpec' => $this->faker->sentence,
            'TechSpec' => $this->faker->sentence,
            'Technika' => $this->faker->text,
            'Skupina' => $this->faker->lexify('??'),
            'Služ' => $this->faker->text,
            'Okolnosti' => $this->faker->text,
            'Lomeni_S' => $this->faker->bothify('##?'),
        ];
    }
}