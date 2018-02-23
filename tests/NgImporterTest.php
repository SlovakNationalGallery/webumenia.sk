<?php

namespace Tests;

use App\Collection;
use App\Import;
use App\Importers\NgImporter;
use App\Repositories\CsvRepository;



class NgImporterTest extends TestCase
{
    use \Tests\DatabaseMigrations;

    public function testId() {
        $data = $this->fakeData(['Ivent. číslo - pracovní' => 'A 0001']);
        $items = $this->importSingle($data);
        $this->assertEquals('CZE:NG.A_0001', $items[0]->id);
    }

    public function testIdentifier() {
        $data = $this->fakeData(['Ivent. číslo - zobrazované' => 'B 0002']);
        $items = $this->importSingle($data);
        $this->assertEquals('B 0002', $items[0]->identifier);
    }

    public function testInscription() {
        $data = $this->fakeData([
            'Značeno (jak např. letopočet, signatura, monogram)' => 'Podpis: Namaloval Čchi Chuang. Pečeť: Starý Paj.',
            'značeno kde (umístění v díle)' => 'vlevo nahoře',
        ]);
        $items = $this->importSingle($data);
        $this->assertEquals('vlevo nahoře: Podpis: Namaloval Čchi Chuang. Pečeť: Starý Paj.', $items[0]->inscription);
    }

    public function testAuthor() {
        $data = $this->fakeData([
            'Autor (jméno příjmení, příp. Anonym)' => 'Prezývka, vlastným menom Meno',
            'Autor 2' => 'Ďalší autor, autorka',
            'Autor 3' => 'Tretí autor',
        ]);
        $items = $this->importSingle($data);
        $this->assertEquals('Prezývka (vlastným menom Meno); Ďalší autor (autorka); Tretí autor', $items[0]->author);
    }

    public function testMeasurement() {
        $data = $this->fakeData([
            'šířka' => '1',
            'výška' => '2',
            'hloubka' => '3',
            'jednotky' => 'cm',
        ]);
        $items = $this->importSingle($data);
        $this->assertEquals('výška 2 cm; šířka 1 cm; hloubka 3 cm', $items[0]->measurement);
    }

    public function testDateEarliest() {
        $data = $this->fakeData(['OSA 1' => '1998']);
        $items = $this->importSingle($data);
        $this->assertEquals('1998', $items[0]->date_earliest);
    }

    public function testDateLatest() {
        $data = $this->fakeData(['OSA 2' => '2003']);
        $items = $this->importSingle($data);
        $this->assertEquals('2003', $items[0]->date_latest);
    }

    public function testGalleryCollection() {
        $data = $this->fakeData(['Sbírka' => 'SGK']);
        $items = $this->importSingle($data);
        $this->assertEquals('Sbírka grafiky a kresby', $items[0]->gallery_collection);
    }

    public function testDating() {
        $data = $this->fakeData(['Datace' => 'datace']);
        $items = $this->importSingle($data);
        $this->assertEquals('datace', $items[0]->dating);
    }

    public function testHasRights() {
        $data = $this->fakeData(['Práva' => 'Ano']);
        $items = $this->importSingle($data);
        $this->assertEquals('1', $items[0]->has_rights);
    }

    public function testCollectionsName() {
        $collection = new Collection();
        $collection->name = 'Veletržní palác';
        $collection->type = 'type';
        $collection->order = 1;
        $collection->save();

        $data = $this->fakeData(['Kolekce (budova)' => 'Veletržní palác']);
        $items = $this->importSingle($data);
        $this->assertCount(1, $items[0]->collections);
        $this->assertEquals('Veletržní palác', $items[0]->collections[0]->name);
    }

    protected function importSingle(array $data) {
        $records = new \ArrayIterator([$data]);
        $repositoryMock = $this->getMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $importer = new NgImporter($repositoryMock);
        $importMock = $this->getMock(Import::class, ['getAttribute']);
        $importMock->method('getAttribute')->willReturn(1);
        $file = ['basename' => '', 'path' => ''];
        $items = $importer->import($importMock, $file);

        $this->assertCount(1, $items);

        return $items;
    }

    protected function fakeData(array $data) {
        return $data + [
            'Práva' => $this->faker->word,
            'Ivent. číslo - zobrazované' => $this->faker->bothify('?? ??'),
            'Ivent. číslo - pracovní' => $this->faker->bothify('?? ??'),
            'Značeno (jak např. letopočet, signatura, monogram)' => $this->faker->sentence,
            'značeno kde (umístění v díle)' => $this->faker->sentence,
            'Autor (jméno příjmení, příp. Anonym)' => $this->faker->name,
            'Autor 2' => $this->faker->name,
            'Autor 3' => $this->faker->name,
            'šířka' => $this->faker->randomNumber,
            'výška' => $this->faker->randomNumber,
            'hloubka' => $this->faker->randomNumber,
            'jednotky' => $this->faker->word,
            'šířka_0' => $this->faker->randomNumber,
            'výška_0' => $this->faker->randomNumber,
            'hloubka_0' => $this->faker->randomNumber,
            'jednotky_0' => $this->faker->word,
            'Rozměr 2' => $this->faker->word,
            'popis rozměru (např. s rámem, se soklem, celý papír apod.)' => $this->faker->sentence,
            'Datace' => $this->faker->year,
            'Datování (určené)' => $this->faker->year,
            'Kolekce (budova)' => $this->faker->word,
            'Sbírka' => $this->faker->word,
            'OSA 1' => $this->faker->year,
            'OSA 2' => $this->faker->year,
            'Materiál' => $this->faker->word,
            'Technika' => $this->faker->word,
        ];
    }
}
