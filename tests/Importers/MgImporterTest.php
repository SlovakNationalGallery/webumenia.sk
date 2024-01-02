<?php

namespace Tests\Importers;

use App\Import;
use App\Importers\MgImporter;
use App\ImportRecord;
use App\Item;
use App\Matchers\AuthorityMatcher;
use App\Repositories\CsvRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class MgImporterTest extends TestCase
{
    use RefreshDatabase;
    use WithoutSearchIndexing;

    protected CsvRepository|MockObject $repositoryMock;

    protected MgImporter $importer;

    public function setUp(): void
    {
        parent::setUp();
        $this->repositoryMock = $this->createMock(CsvRepository::class);
        $this->importer = new MgImporter(
            app(AuthorityMatcher::class),
            $this->repositoryMock,
            app(Translator::class)
        );
    }

    /** @dataProvider mediumProvider */
    public function testMedium($material, $matSpec, array $translations)
    {
        $this->importData([
            'Materiál' => $material,
            'MatSpec' => $matSpec,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->medium);
        $this->assertEquals($translations['sk'], $item->translate('sk')->medium);
        $this->assertEquals($translations['en'], $item->translate('en')->medium);
    }

    public static function mediumProvider()
    {
        return [
            'medium' => [
                'papír',
                null,
                [
                    'cs' => 'papír',
                    'sk' => 'papier',
                    'en' => 'paper',
                ],
            ],
            'unexistingMedium' => [
                'unknown',
                null,
                [
                    'cs' => 'unknown',
                    'sk' => null,
                    'en' => null,
                ],
            ],
            'mediumSpec' => [
                'papír',
                'matný',
                [
                    'cs' => 'papír/matný',
                    'sk' => 'papier/matný',
                    'en' => 'paper/matte',
                ],
            ],
            'mediumUnexistingSpec' => [
                'papír',
                'unknown',
                [
                    'cs' => 'papír/unknown',
                    'sk' => null,
                    'en' => null,
                ],
            ],
        ];
    }

    /** @dataProvider techniqueProvider */
    public function testTechnique($technika, $techSpec, array $translations)
    {
        $this->importData([
            'Technika' => $technika,
            'TechSpec' => $techSpec,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->technique);
        $this->assertEquals($translations['sk'], $item->translate('sk')->technique);
        $this->assertEquals($translations['en'], $item->translate('en')->technique);
    }

    public static function techniqueProvider()
    {
        return [
            'technique' => [
                'akvarel',
                null,
                [
                    'cs' => 'akvarel',
                    'sk' => 'akvarel',
                    'en' => 'watercolor',
                ],
            ],
            'unexistingTechnique' => [
                'unknown',
                null,
                [
                    'cs' => 'unknown',
                    'sk' => null,
                    'en' => null,
                ],
            ],
            'techniqueSpec' => [
                'akvarel',
                'černý',
                [
                    'cs' => 'akvarel/černý',
                    'sk' => 'akvarel/čierny',
                    'en' => 'watercolor/black',
                ],
            ],
        ];
    }

    /** @dataProvider workTypeProvider */
    public function testWorkType($skupina, $podskup, array $translations)
    {
        $this->importData([
            'Skupina' => $skupina,
            'Podskup' => $podskup,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->work_type);
        $this->assertEquals($translations['sk'], $item->translate('sk')->work_type);
        $this->assertEquals($translations['en'], $item->translate('en')->work_type);
    }

    public static function workTypeProvider()
    {
        return [
            'workType' => [
                'Fo',
                null,
                [
                    'cs' => 'fotografie',
                    'sk' => 'fotografia',
                    'en' => 'photograph',
                ],
            ],
            'unexistingWorkType' => [
                'unknown',
                null,
                [
                    'cs' => null,
                    'sk' => null,
                    'en' => null,
                ],
            ],
            'workTypeSpec' => [
                'Fo',
                'negativ',
                [
                    'cs' => 'fotografie/negativ',
                    'sk' => 'fotografia/negatív',
                    'en' => 'photograph/negative',
                ],
            ],
            'workTypeUnexistingSpec' => [
                'Fo',
                'unknown',
                [
                    'cs' => 'fotografie/unknown',
                    'sk' => null,
                    'en' => null,
                ],
            ],
        ];
    }

    /** @dataProvider topicProvider */
    public function testTopic($topic, array $translations)
    {
        $this->importData([
            'Námět' => $topic,
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals($translations['cs'], $item->translate('cs')->topic);
        $this->assertEquals($translations['sk'], $item->translate('sk')->topic);
        $this->assertEquals($translations['en'], $item->translate('en')->topic);
    }

    public static function topicProvider()
    {
        return [
            'topic' => [
                'figurální kompozice',
                [
                    'cs' => 'figurální kompozice',
                    'sk' => 'figurálna kompozícia',
                    'en' => 'figurative composition',
                ],
            ],
            'unexistingTopic' => [
                'unknown',
                [
                    'cs' => 'unknown',
                    'sk' => null,
                    'en' => null,
                ],
            ],
        ];
    }

    public function testBiennalBrno()
    {
        $this->importData([
            'Okolnosti' => 'BB',
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals('ze souboru', $item->translate('cs')->relationship_type);
        $this->assertEquals('zo súboru', $item->translate('sk')->relationship_type);
        $this->assertEquals('from set', $item->translate('en')->relationship_type);
        $this->assertEquals('Bienále Brno', $item->translate('cs')->related_work);
        $this->assertEquals('Bienále Brno', $item->translate('sk')->related_work);
        $this->assertEquals('Biennal Brno', $item->translate('en')->related_work);
    }

    public function testJiriValochArchive()
    {
        $this->importData([
            'Rada_S' => 'JV',
        ]);

        $item = Item::find('CZE:MG.JV_123-lomeni_s');
        $this->assertEquals('ze souboru', $item->translate('cs')->relationship_type);
        $this->assertEquals('zo súboru', $item->translate('sk')->relationship_type);
        $this->assertEquals('from set', $item->translate('en')->relationship_type);
        $this->assertEquals('Archiv a sbírka Jiřího Valocha', $item->translate('cs')->related_work);
        $this->assertEquals(
            'Archív a zbierka Jiřího Valocha',
            $item->translate('sk')->related_work
        );
        $this->assertEquals(
            'Archive and collection of Jiří Valoch',
            $item->translate('en')->related_work
        );
    }

    public function testMeasurement()
    {
        $this->importData([
            'Služ' => 's=4,6cm; d=6cm; a=2cm',
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals(
            'šířka 4,6 cm; délka 6 cm; výška hlavní části 2 cm',
            $item->translate('cs')->measurement
        );
        $this->assertEquals(
            'šírka 4,6 cm; dĺžka 6 cm; výška hlavnej časti 2 cm',
            $item->translate('sk')->measurement
        );
        $this->assertEquals(
            'width 4.6 cm; length 6 cm; height of the main part 2 cm',
            $item->translate('en')->measurement
        );
    }

    public function testDating()
    {
        $this->importData([
            'Datace' => '18. stol.',
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals('18. stol.', $item->translate('cs')->dating);
        $this->assertEquals('18. stol.', $item->translate('sk')->dating);
        $this->assertEquals(null, $item->translate('en')->dating);
    }

    /** @dataProvider titleProvider */
    public function testTitle($titul, $predmet, array $translations)
    {
        $this->importData([
            'Titul' => $titul,
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
            'title' => [
                'Madona s dítětem',
                null,
                [
                    'cs' => 'Madona s dítětem',
                    'sk' => 'Madona s dítětem',
                    'en' => null,
                ],
            ],
            'subject' => [
                null,
                'krabička',
                [
                    'cs' => 'krabička',
                    'sk' => 'krabička',
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

    public function testExhibition()
    {
        $this->importData([
            'AktLokace' => 'UPM/210/F/B04/p01',
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals('BLACK DEPO', $item->exhibition);
    }

    public function testBox()
    {
        $this->importData([
            'AktLokace' => 'UPM/210/F/B04/p01',
        ]);

        $item = Item::find('CZE:MG.rada_s_123-lomeni_s');
        $this->assertEquals('BOX F04', $item->box);
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
            'Lomeni_S' => 'lomeni_s',
            'PorC_S' => 123,
            'Datace' => $this->faker->sentence,
            'RokOd' => $this->faker->year,
            'Do' => $this->faker->year,
            'MístoVz' => $this->faker->city,
            'Sign' => $this->faker->sentence,
            'Původnost' => $this->faker->sentence,
            'Autor' => $this->faker->name,
            'Titul' => $this->faker->sentence,
            'Námět' => $this->faker->sentence,
            'Plus2T' => $this->faker->word,
            'Materiál' => $this->faker->sentence,
            'MatSpec' => $this->faker->sentence,
            'TechSpec' => $this->faker->sentence,
            'Technika' => $this->faker->sentence,
            'Skupina' => 'Ar',
            'Podskup' => $this->faker->word,
            'Služ' => $this->faker->sentence,
            'Okolnosti' => $this->faker->text,
            'AktLokace' => $this->faker->word,
        ];
    }
}
