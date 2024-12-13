<?php

namespace Tests\Importers;

use App\Import;
use App\Importers\UpmImporter;
use App\ImportRecord;
use App\Item;
use App\Matchers\AuthorityMatcher;
use App\Repositories\CsvRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class UpmImporterTest extends TestCase
{
    use RefreshDatabase, WithoutSearchIndexing;

    use WithoutSearchIndexing;

    protected CsvRepository|MockObject $repositoryMock;

    protected UpmImporter $importer;

    public function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = $this->createMock(CsvRepository::class);
        $this->importer = new UpmImporter(
            app(AuthorityMatcher::class),
            $this->repositoryMock,
            app(Translator::class)
        );
    }

    public function testImport()
    {
        $this->importData();

        $item = Item::find('CZE:UPM.GK_11090');

        $this->assertEquals('GK 11090/E', $item->identifier);
        $this->assertEquals('Jiří Rathouský – grafická úprava, design vazby a přebalu', $item->author);
        $this->assertEquals(1928, $item->date_earliest);
        $this->assertEquals(1928, $item->date_latest);
        $this->assertEquals('1986', $item->acquisition_date);
        $this->assertEquals('Vazba knihy', $item->translate('sk')->title);
        $this->assertEquals('Vazba knihy', $item->translate('cs')->title);
        $this->assertEquals('Binding design', $item->translate('en')->title);
        $this->assertEquals('celková výška/dĺžka 16,5cm (konvice čajová s víčkem větší); celková výška/dĺžka 13,5-14cm (konvice čajová s víčkem menší)', $item->translate('sk')->measurement);
        $this->assertEquals('celková výška/délka 16,5cm (konvice čajová s víčkem větší); celková výška/délka 13,5-14cm (konvice čajová s víčkem menší)', $item->translate('cs')->measurement);
        $this->assertEquals('overall height/length 16,5cm (konvice čajová s víčkem větší); overall height/length 13,5-14cm (konvice čajová s víčkem menší)', $item->translate('en')->measurement);
        $this->assertEquals('zo súboru', $item->translate('sk')->relationship_type);
        $this->assertEquals('ze souboru', $item->translate('cs')->relationship_type);
        $this->assertEquals('collection', $item->translate('en')->relationship_type);
        $this->assertEquals('Sbírka užité grafiky', $item->translate('cs')->related_work);
        $this->assertEquals('užité umění;grafický design', $item->translate('cs')->work_type); // todo translate
        $this->assertEquals('celokožená vazba, zlacení', $item->translate('cs')->technique); // todo translate
        $this->assertEquals('kniha', $item->translate('cs')->object_type); // todo translate
        $this->assertEquals('kůže;papír', $item->translate('cs')->medium); // todo translate
        $this->assertEquals('ornament', $item->translate('cs')->topic); // todo translate
        $this->assertEquals('1928', $item->translate('cs')->dating);
        $this->assertEquals('Praha', $item->translate('cs')->place);
        $this->assertEquals('Rösller', $item->translate('cs')->inscription);
        $this->assertEquals('VŠUP atelier V.H.Brunnera (vazba)', $item->translate('cs')->additionals['producer']);
        $this->assertEquals('převod', $item->translate('cs')->additionals['acquisition']);
        $this->assertEquals('Japonsko design, 2019-2020', $item->translate('cs')->additionals['exhibition']);
    }

    private function importData(array $data = []): ImportRecord
    {
        $data = $this->fakeData($data);

        $this->repositoryMock->method('getFiltered')->willReturn(new \ArrayIterator([$data]));

        $importRecord = ImportRecord::factory()
            ->for(Import::factory())
            ->create();
        $this->importer->import($importRecord, stream: null);
        return $importRecord;
    }

    private function fakeData(array $overrides = []): array
    {
        return $overrides + [
            "Inventárníčíslo" => "GK 11090/E",
            "ID" => "GK_11090",
            "Název" => "Vazba knihy",
            "Název EN" => "Binding design",
            "Autor" => "Rathouský, Jiří – grafická úprava, design vazby a přebalu",
            "Vznik" => "Praha;VŠUP atelier V.H.Brunnera (vazba)",
            "Datace" => "1928",
            "Od" => "1928",
            "Do" => "1928",
            "Výtvarný druh" => "užité umění;grafický design",
            "Typ" => "kniha",
            "Materiál" => "kůže;papír",
            "Technika" => "celokožená vazba, zlacení",
            "Rozměry" => "v=16,5cm (konvice čajová s víčkem větší); v=13,5-14cm (konvice čajová s víčkem menší)",
            "Námět" => "ornament",
            "tagy" => "",
            "Značení" => "Rösller",
            "Způsob akvizice" => "převod",
            "Datum akvizice" => "1986",
            "Výstava" => "Japonsko design, 2019-2020",
            "Publikovat" => "Y",
            "Sbírka" => "Sbírka užité grafiky",
            "" => "Kolbersb",
        ];
    }
}