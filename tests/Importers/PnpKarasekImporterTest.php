<?php

namespace Tests\Importers;

use App\Import;
use App\Importers\PnpKarasekImporter;
use App\Matchers\AuthorityMatcher;
use App\Repositories\CsvRepository;
use Illuminate\Contracts\Translation\Translator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use SplFileInfo;
use Tests\TestCase;

class PnpKarasekImporterTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('DG_PUBLIC_IS');
    }

    public function testId()
    {
        $data = $this->getData([
            'Inventární číslo:' => 'IO 221',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('CZE:PNP.IO_221', $item->id);
    }

    public function testWorkType()
    {
        $data = $this->getData([
            'Výtvarný druh:' => 'Malba',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('malířství', $item->getTranslationOrFail('cs')->work_type);
        $this->assertEquals('maliarstvo', $item->getTranslationOrFail('sk')->work_type);
        $this->assertEquals('painting', $item->getTranslationOrFail('en')->work_type);
    }

    public function testTopic()
    {
        $data = $this->getData([
            'Námět:' => 'Architektura, portrét',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('architektura; portrét', $item->getTranslationOrFail('cs')->topic);
        $this->assertEquals('architektúra; portrét', $item->getTranslationOrFail('sk')->topic);
        $this->assertEquals('architecture; portrait', $item->getTranslationOrFail('en')->topic);
    }

    public function testTopicWithoutTranslation()
    {
        $data = $this->getData([
            'Námět:' => 'not translated',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('not translated', $item->getTranslationOrFail('cs')->topic);
        $this->assertEquals(null, $item->getTranslationOrFail('sk')->topic);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->topic);
    }

    public function testMedium()
    {
        $data = $this->getData([
            'Materiál:' => 'Papír',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('papír', $item->getTranslationOrFail('cs')->medium);
        $this->assertEquals('papier', $item->getTranslationOrFail('sk')->medium);
        $this->assertEquals('paper', $item->getTranslationOrFail('en')->medium);
    }

    public function testMediumWithoutTranslation()
    {
        $data = $this->getData([
            'Materiál:' => 'not translated',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('not translated', $item->getTranslationOrFail('cs')->medium);
        $this->assertEquals(null, $item->getTranslationOrFail('sk')->medium);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->medium);
    }

    public function testTechnique()
    {
        $data = $this->getData([
            'Technika:' => 'Mědiryt, lept',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('mědiryt; lept', $item->getTranslationOrFail('cs')->technique);
        $this->assertEquals('medirytina; lept', $item->getTranslationOrFail('sk')->technique);
        $this->assertEquals('copperplate; etching', $item->getTranslationOrFail('en')->technique);
    }

    public function testTechniqueWithoutTranslation()
    {
        $data = $this->getData([
            'Technika:' => 'not translated',
        ]);
        $item = $this->importSingle($data);
        $this->assertEquals('not translated', $item->getTranslationOrFail('cs')->technique);
        $this->assertEquals(null, $item->getTranslationOrFail('sk')->technique);
        $this->assertEquals(null, $item->getTranslationOrFail('en')->technique);
    }

    protected function importSingle(array $data)
    {
        $records = new \ArrayIterator([$data]);
        $repositoryMock = $this->createMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $importer = new PnpKarasekImporter(
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
            'Soubor:' => 'Slovanská galerie',
            'Kategorie:' => 'Polské umění',
            'Autor:' => 'Czajkowski, Stanislaw',
            'Datum narození:' => '1878',
            'Datum úmrtí:' => '1954',
            'Alternatívni jméno:' => '',
            'Role:' => '',
            'Název:' => 'Krajina s kapličkou',
            'Datace:' => '1920',
            'Rok od:' => '1920',
            'Rok do:' => '1920',
            'Rozměry:' => '55 x 89 cm',
            'Výtvarný druh:' => 'Malba',
            'Námět:' => 'Krajina',
            'Materiál:' => 'Plátno',
            'Technika:' => 'Olej',
            'Inventární číslo:' => 'IO 263',
            'Značení:' => 'Stanislaw Czajkowski 1920',
            'Popis:' => '',
        ];
    }
}
