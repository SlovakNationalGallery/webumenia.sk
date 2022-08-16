<?php

namespace Tests\Importers;

use App\Import;
use App\Importers\OglImporter;
use App\Matchers\AuthorityMatcher;
use App\Repositories\CsvRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SplFileInfo;
use Tests\TestCase;

class OglImporterTest extends TestCase
{
    use RefreshDatabase;

    public function testId()
    {
        $data = $this->getData([
            'Rada_S' => 'rada_s',
            'Lomeni_S' => 'lomeni_s',
            'PorC_S' => 123,
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('CZE:OGL.rada_s_123-lomeni_s', $item->id);
    }

    public function testIdentifier()
    {
        $data = $this->getData([
            'Rada_S' => 'rada_s',
            'Lomeni_S' => 'lomeni_s',
            'PorC_S' => 123,
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('rada_s 123/lomeni_s', $item->identifier);
    }

    public function testMediumSimple()
    {
        $data = $this->getData([
            'Materiál' => 'dřevo',
            'MatSpec' => '',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('dřevo', $item->getTranslationOrFail('cs')->medium);
        $this->assertEquals('drevo', $item->getTranslationOrFail('sk')->medium);
        $this->assertEquals('wood', $item->getTranslationOrFail('en')->medium);
    }

    public function testMediumFirstOmitted()
    {
        $data = $this->getData([
            'Materiál' => '',
            'MatSpec' => 'not translated',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('not translated', $item->getTranslationOrFail('cs')->medium);
        $this->assertEquals(null, $item->getTranslationOrFail('sk')->medium);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->medium);
    }

    public function testMediumCompound()
    {
        $data = $this->getData([
            'Materiál' => 'dřevo',
            'MatSpec' => 'not translated',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('dřevo, not translated', $item->getTranslationOrFail('cs')->medium);
        $this->assertEquals('drevo', $item->getTranslationOrFail('sk')->medium);
        $this->assertEquals('wood', $item->getTranslationOrFail('en')->medium);
    }

    public function testTechniqueSimple()
    {
        $data = $this->getData([
            'Technika' => 'lití',
            'TechSpec' => '',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('lití', $item->getTranslationOrFail('cs')->technique);
        $this->assertEquals('liatie', $item->getTranslationOrFail('sk')->technique);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->technique);
    }

    public function testTechniqueCompound()
    {
        $data = $this->getData([
            'Technika' => 'lakování',
            'TechSpec' => 'železné háčky',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals(
            'lakování, železné háčky',
            $item->getTranslationOrFail('cs')->technique
        );
        $this->assertEquals(
            'lakovanie, železné háčiky',
            $item->getTranslationOrFail('sk')->technique
        );
        $this->assertEquals(null, $item->getTranslationOrFail('en')->technique);
    }

    public function testTechniqueWithoutTranslation()
    {
        $data = $this->getData([
            'Technika' => 'not translated',
            'TechSpec' => '',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('not translated', $item->getTranslationOrFail('cs')->technique);
        $this->assertEquals(null, $item->getTranslationOrFail('sk')->technique);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->technique);
    }

    public function testTopicWithoutTranslation()
    {
        $data = $this->getData([
            'Námět' => 'not translated',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('not translated', $item->getTranslationOrFail('cs')->topic);
        $this->assertEquals(null, $item->getTranslationOrFail('sk')->topic);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->topic);
    }

    public function testWorkType()
    {
        $data = $this->getData([
            'Skupina' => 'Ob',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('malířství', $item->getTranslationOrFail('cs')->work_type);
        $this->assertEquals('maliarstvo', $item->getTranslationOrFail('sk')->work_type);
        $this->assertEquals('painting', $item->getTranslationOrFail('en')->work_type);
    }

    public function testMeasurement()
    {
        $data = $this->getData([
            'SlužF' => 'v=140cm; s=125cm; vr=141cm; sr=127,3cm; hr=3,3cm',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals(
            'celková výška/délka 140 cm; šířka 125 cm; výška s rámem 141 cm; šířka s rámem 127,3 cm; hloubka s rámem 3,3 cm',
            $item->getTranslationOrFail('cs')->measurement
        );
        $this->assertEquals(
            'celková výška/dĺžka 140 cm; šírka 125 cm; výška s rámom 141 cm; šírka s rámom 127,3 cm; hĺbka s rámom 3,3 cm',
            $item->getTranslationOrFail('sk')->measurement
        );
        $this->assertEquals(
            'overall height/length 140 cm; width 125 cm; height with frame 141 cm; width with frame 127,3 cm; depth with frame 3,3 cm',
            $item->getTranslationOrFail('en')->measurement
        );
    }

    public function testStylePeriod()
    {
        $data = $this->getData([
            'Podskup' => 'České umění 20. st.',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('České umění 20. st.', $item->getTranslationOrFail('cs')->style_period);
        $this->assertEquals(
            'České umenie 20. st.',
            $item->getTranslationOrFail('sk')->style_period
        );
        $this->assertEquals(null, $item->getTranslationOrFail('en')->style_period);
    }

    public function testStylePeriodWithoutTranslation()
    {
        $data = $this->getData([
            'Podskup' => 'not translated',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('not translated', $item->getTranslationOrFail('cs')->style_period);
        $this->assertEquals(null, $item->getTranslationOrFail('sk')->style_period);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->style_period);
    }

    protected function importSingle(array $data)
    {
        $records = new \ArrayIterator([$data]);
        $repositoryMock = $this->createMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $importer = new OglImporter(
            $this->app->get(AuthorityMatcher::class),
            $repositoryMock,
            $this->app->get(Translator::class)
        );
        $import = Import::create();
        $items = $importer->import($import, new SplFileInfo(''));

        $this->assertEquals('', $import->records()->first()->error_message);
        $this->assertCount(1, $items);

        return $items[0];
    }

    public function getData(array $overrides)
    {
        return $overrides + [
            'Rada_S' => 'P',
            'PorC_S' => '269',
            'Lomeni_S' => '_',
            'RokAkv' => '1999',
            'Autor' => 'Nikl, Petr',
            'DatExp' => '09.09.9999',
            'Datace' => '1995',
            'RokOd' => '1995',
            'Do' => '1995',
            'MístoVz' => '',
            'Předmět' => 'sochařství',
            'Titul' => 'Opička',
            'Skupina' => 'So',
            'Podskup' => 'České umění 20. st.',
            'Materiál' => '',
            'MatSpec' => 'motoristická přilba',
            'Technika' => 'objekt',
            'TechSpec' => '',
            'Původnost' => 'originální autorské dílo',
            'Námět' => 'Fauna',
            'Sign' => 'neznačeno',
            'AktLokace' => 'Expozice Na vlnách umění',
            'DatZměny' => '02.02.2022',
            'Plus2T' => '',
            'Služ' => '',
            'SlužF' => 'v=14cm; p=50,1cm',
            'Okolnosti' => '',
        ];
    }
}
