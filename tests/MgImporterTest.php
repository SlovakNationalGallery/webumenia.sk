<?php

namespace Tests;

use App\Import;
use App\Repositories\CsvRepository;
use App\Importers\MgImporter;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MgImporterTest extends TestCase
{
    use DatabaseMigrations;

    public function testImport() {
        $data = [
            'RokAkv' => '2017',
            'DatExp' => 'datexp',
            'Datace' => 'datace',
            'RokOd' => '2016',
            'Do' => '2017',
            'MístoVz' => 'mistovz',
            'Sign' => 'sign',
            'Původnost' => 'puvodnost',
            'Autor' => 'autor',
            'Titul' => 'titul',
            'Námět' => 'namet',
            'Plus2T' => 'plus2t',
            'Rada_S' => 'rada_s',
            'PorC_S' => 'porc_s',
            'Materiál' => 'material',
            'MatSpec' => 'matspec',
            'TechSpec' => 'techspec',
            'Technika' => 'technika',
            'Skupina' => 'Ar',
            'Služ' => 's=4,6cm; d=6cm; a=2cm',
            'Okolnosti' => 'BB',
            'Lomeni_S' => 'lomeni_s',
        ];

        $records = new \ArrayIterator([$data]);

        $repositoryMock = $this->getMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $this->importer = new MgImporter($repositoryMock);

        $importMock = $this->getMock(Import::class, ['getAttribute']);
        $importMock->method('getAttribute')->willReturn(1);
        $file = ['basename' => '', 'path' => ''];

        $items = $this->importer->import($importMock, $file);

        $this->assertCount(1, $items);

        $expected = [
            'id' => 'CZE:MG.rada_s_0-lomeni_s',
            'acquisition_date' => 2017,
            'copyright_expires' => 'datexp',
            'dating' => 'datace',
            'date_earliest' => 2016,
            'date_latest' => 2017,
            'place' => 'mistovz',
            'inscription' => 'sign',
            'state_edition' => 'puvodnost',
            'author' => 'autor',
            'title' => 'titul',
            'topic' => 'namet',
            'gallery' => 'Moravská galerie, MG',
            'identifier' => 'rada_s 0/lomeni_s',
            'medium' => 'material, matspec',
            'technique' => 'technika, techspec',
            'relationship_type' => 'ze souboru',
            'work_type' => 'architektura',
            'related_work' => 'Bienále Brno',
            'measurement' => 'šířka 4,6 cm; délka 6 cm; výška hlavní části 2 cm',
        ];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $items[0]->$key);
        }
    }
}