<?php

namespace Tests\Importers;

use App\Import;
use App\Importers\MmpImporter;
use App\ImportRecord;
use App\Item;
use App\Matchers\AuthorityMatcher;
use App\Repositories\CsvRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class MmpImporterTest extends TestCase
{
    use RefreshDatabase, WithoutSearchIndexing;

    protected CsvRepository|MockObject $repositoryMock;

    protected MmpImporter $importer;

    public function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = $this->createMock(CsvRepository::class);
        $this->importer = new MmpImporter(
            app(AuthorityMatcher::class),
            $this->repositoryMock,
            app(Translator::class)
        );
    }

    public function testImport()
    {
        $this->importData();

        $item = Item::find('CZE:MP.H_014_487');

        $this->assertEquals('H 014 487', $item->identifier);
        $this->assertEquals('Beisch, Josef Antonín', $item->author);
        $this->assertEquals(1701, $item->date_earliest);
        $this->assertEquals(1800, $item->date_latest);
        $this->assertEquals('Panna Marie Karlovská', $item->title);
        $this->assertEquals('počátek 18.stol.', $item->dating);
        $this->assertEquals('maliarstvo', $item->translate('sk')->work_type);
        $this->assertEquals('malířství', $item->translate('cs')->work_type);
        $this->assertEquals('painting', $item->translate('en')->work_type);
        $this->assertEquals('olej', $item->translate('sk')->technique);
        $this->assertEquals('olej', $item->translate('cs')->technique);
        $this->assertEquals('oil', $item->translate('en')->technique);
        $this->assertEquals('drevo', $item->translate('sk')->medium);
        $this->assertEquals('dřevo', $item->translate('cs')->medium);
        $this->assertEquals('wood', $item->translate('en')->medium);
        $this->assertEquals(null, $item->translate('sk')->topic);
        $this->assertEquals('obraz náboženský', $item->translate('cs')->topic);
        $this->assertEquals(null, $item->translate('en')->topic);
        $this->assertEquals('', $item->translate('sk')->measurement);
        $this->assertEquals('', $item->translate('cs')->measurement);
        $this->assertEquals('', $item->translate('en')->measurement);
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
            "Řada" => "H",
            "Inventární číslo" => "H 014 487",
            "Titul" => "Panna Marie Karlovská",
            "Autor" => "Beisch Josef Antonín",
            "Datace vzniku" => "počátek 18.stol.",
            "(n) Datace OD" => "1.1.1701",
            "(n) Datace DO" => "31.12.1800",
            "Výtvarný druh" => "malba",
            "Materiál" => "dřevo",
            "Technika" => "olej",
            "Rozměr" => "rv.=31cm; rš.=21,5cm; v-cm=24,5cm; š-cm=14,5cm",
            "Námět/téma" => "obraz náboženský",
            "Signatura" => "",
        ];
    }
}