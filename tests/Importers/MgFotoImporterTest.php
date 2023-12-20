<?php

namespace Tests\Importers;

use App\Import;
use App\Importers\MgFotoImporter;
use App\ImportRecord;
use App\Item;
use App\Matchers\AuthorityMatcher;
use App\Repositories\CsvRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Translation\Translator;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class MgFotoImporterTest extends TestCase
{
    use RefreshDatabase;
    use WithoutSearchIndexing;

    protected CsvRepository|MockObject $repositoryMock;

    protected MgFotoImporter $importer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = $this->createMock(CsvRepository::class);
        $this->importer = new MgFotoImporter(
            app(AuthorityMatcher::class),
            $this->repositoryMock,
            app(Translator::class)
        );
    }

    /** @dataProvider mediumProvider */
    public function testMedium($material, $povrch, $barva, $translations)
    {
        $this->importData([
            'Materiál' => $material,
            'Povrch' => $povrch,
            'Barva' => $barva,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->medium);
        $this->assertEquals($translations['sk'], $item->translate('sk')->medium);
        $this->assertEquals($translations['en'], $item->translate('en')->medium);
    }

    public static function mediumProvider()
    {
        return [
            'material' => [
                'papír',
                null,
                null,
                [
                    'cs' => 'papír',
                    'sk' => 'papier',
                    'en' => 'paper',
                ],
            ],
            'materialWithSurface' => [
                'papír',
                'M',
                null,
                [
                    'cs' => 'papír/matný',
                    'sk' => 'papier/matný',
                    'en' => 'paper/matte',
                ],
            ],
            'materialWithColor' => [
                null,
                null,
                'CP',
                [
                    'cs' => 'papír/pastelový papír',
                    'sk' => 'papier/pastelový papier',
                    'en' => 'paper/pastel paper',
                ],
            ],
        ];
    }

    /** @dataProvider titleProvider */
    public function testTitle($nazev, $predmet, $translations)
    {
        $this->importData([
            'Název' => $nazev,
            'Předmět' => $predmet,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->title);
        $this->assertEquals($translations['sk'], $item->translate('sk')->title);
        $this->assertEquals($translations['en'], $item->translate('en')->title);
    }

    public static function titleProvider()
    {
        return [
            'nazev' => [
                'nazev',
                null,
                [
                    'cs' => 'nazev',
                    'sk' => 'nazev',
                    'en' => null,
                ],
            ],
            'predmet' => [
                null,
                'predmet',
                [
                    'cs' => 'predmet',
                    'sk' => 'predmet',
                    'en' => null,
                ],
            ],
            'untitled' => [
                null,
                null,
                [
                    'cs' => 'bez názvu',
                    'sk' => 'bez názvu',
                    'en' => 'untitled',
                ],
            ],
        ];
    }

    public function testStateEdition()
    {
        $this->importData([
            'Původnost' => 'O',
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals('originál', $item->translate('cs')->state_edition);
        $this->assertEquals('originál', $item->translate('sk')->state_edition);
        $this->assertEquals('original', $item->translate('en')->state_edition);
    }

    /** @dataProvider workTypeProvider */
    public function testWorkType($predmet, $translations)
    {
        $this->importData([
            'Předmět' => $predmet,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->work_type);
        $this->assertEquals($translations['sk'], $item->translate('sk')->work_type);
        $this->assertEquals($translations['en'], $item->translate('en')->work_type);
    }

    public static function workTypeProvider()
    {
        return [
            'noSubject' => [
                null,
                [
                    'cs' => 'fotografie',
                    'sk' => 'fotografia',
                    'en' => 'photograph',
                ],
            ],
            'subject' => [
                'negativ',
                [
                    'cs' => 'fotografie/negativ',
                    'sk' => 'fotografia/negatív',
                    'en' => 'photograph/negative',
                ],
            ],
        ];
    }

    /** @dataProvider techniqueProvider */
    public function testTechnique($zoom, $barva, $translations)
    {
        $this->importData([
            'Zoom' => $zoom,
            'Barva' => $barva,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->technique);
        $this->assertEquals($translations['sk'], $item->translate('sk')->technique);
        $this->assertEquals($translations['en'], $item->translate('en')->technique);
    }

    public static function techniqueProvider()
    {
        return [
            'zoom' => [
                'V',
                null,
                [
                    'cs' => 'zvětšování',
                    'sk' => 'zväčšovanie',
                    'en' => 'enlarging',
                ],
            ],
            'barva' => [
                null,
                'C',
                [
                    'cs' => 'černobílá fotografie',
                    'sk' => 'čiernobiela fotografia',
                    'en' => 'black-and-white photograph',
                ],
            ],
            'zoom/barva' => [
                'V',
                'C',
                [
                    'cs' => 'zvětšování; černobílá fotografie',
                    'sk' => 'zväčšovanie; čiernobiela fotografia',
                    'en' => 'enlarging; black-and-white photograph',
                ],
            ],
        ];
    }

    public function testDagmarHochovaArchive()
    {
        $this->importData([
            'Okolnosti' => 'Archiv negativů Dagmar Hochové',
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals('ze souboru', $item->translate('cs')->relationship_type);
        $this->assertEquals('zo súboru', $item->translate('sk')->relationship_type);
        $this->assertEquals('from set', $item->translate('en')->relationship_type);
        $this->assertEquals('Archiv negativů Dagmar Hochové', $item->translate('cs')->related_work);
        $this->assertEquals(
            'Archív negatívov Dagmar Hochovej',
            $item->translate('sk')->related_work
        );
        $this->assertEquals(
            'Archive of negatives of Dagmar Hochová',
            $item->translate('en')->related_work
        );
    }

    protected function importData($data): ImportRecord
    {
        $data = $this->fakeData($data);

        $this->repositoryMock->method('getFiltered')->willReturn(new \ArrayIterator([$data]));

        $importRecord = ImportRecord::factory()
            ->for(Import::factory())
            ->create();
        $this->importer->import($importRecord, stream: null);
        return $importRecord;
    }

    protected function fakeData(array $overrides = []): array
    {
        return $overrides + [
            'Rada_S' => 'rada_s',
            'PorC_S' => 123,
            'Lomeni_S' => 'lomeni_s',
            'až' => null,
            '/' => null,
            'Stát' => 'CZE',
            'Spr' => '6BG',
            'Odd' => 'f',
            'Karta' => 'PRAVDA',
            'Opsána' => 'NEPRAVDA',
            'Ověřena' => 'NEPRAVDA',
            'Kopie_S' => 'NEPRAVDA',
            'Ozn' => 'NEPRAVDA',
            'PrirC_S' => 'NEPRAVDA',
            'JináČ' => 'neg.',
            'Zapsal' => 'TAČR - MM',
            'DatZap' => '16.11.2007',
            'Počet' => '1',
            'StLokace' => 'Z 25',
            'AktLokace' => 'Z 25',
            'Určil' => 'Dufek',
            'DatUrč' => '08.12.1969',
            'Podsb' => '15',
            'Autor' => 'Reichmann, Vilém',
            'Profi_S' => 'NEPRAVDA',
            'Ateliér' => null,
            'MístoPůs' => 'Brno (Brünn)',
            'SigZnA' => null,
            'SigZnR' => 'název, signatura, datace',
            'Původnost' => 'O',
            'Materiál' => 'papír',
            'Předmět' => 'pozitiv',
            'Zoom' => 'V',
            'Barva' => 'C',
            'Povrch' => null,
            'Adj' => 'P',
            'Orientace' => 'V',
            'Formát' => '30x40',
            'Stav' => '1',
            'DatSt' => '25.10.2006',
            'Čitelnost' => 'a',
            'Osetrit_S' => 'NEPRAVDA',
            'Ktg' => null,
            'Téma' => null,
            'Název' => 'Bez názvu',
            'Popis' => 'Světlomet na louce.',
            'Lokalita' => null,
            'SpecLok' => null,
            'Čtverec' => null,
            'DatVz' => '1949',
            'RokVzOd' => '1949',
            'RokVzDo' => '1949',
            'KlíčObs' => null,
            'RokObsOd' => null,
            'RokObsDo' => null,
            'DatObs' => null,
            'PopisSt' => null,
            'Markant' => null,
            'PopisAdj' => null,
            'Vazba' => null,
            'Ošetření' => null,
            'RokOš' => null,
            'DatRev' => '31.12.2011',
            'InvRev' => null,
            'Pozn' => 'dobová zvětšenina',
            'Plus1T' => null,
            'Plus2T' => 'fotografie',
            'Plus3Č' => null,
            'Služ' => 's=302mm; v=349mm',
            'SlužF' => 's=302mm; v=349mm',
            'Služ2' => null,
            'ČDoklAkv' => null,
            'ZpAkv' => null,
            'DatAkv' => null,
            'PředMajSlovy' => null,
            'Okolnosti' => null,
        ];
    }
}
