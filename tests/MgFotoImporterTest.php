<?php

namespace Tests;

use App\Import;
use App\Importers\MgFotoImporter;
use App\Repositories\CsvRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class MgFotoImporterTest extends TestCase
{
    use DatabaseMigrations;

    public function testImport() {
        $data = [
            'Rada_S' => 'MGA',
            'PorC_S' => 'porc_s',
            'Lomeni_S' => 'lomeni_s',
            'DatVz' => '1981',
            'RokVzOd' => '1980',
            'RokVzDo' => '1982',
            'Původnost' => 'O',
            'Autor' => 'autor',
            'Titul' => 'titul',
            'Plus2T' => 'plus2t',
            'Materiál' => 'material',
            'Služ' => 's=4,6cm; d=6cm; a=2cm',
            'Okolnosti' => 'BB',
            'Předmět' => 'pozitiv',
            'Povrch' => 'M',
            'Zoom' => 'M',
            'Barva' => 'C',
        ];

        $records = new \ArrayIterator([$data]);

        $repositoryMock = $this->getMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $this->importer = new MgFotoImporter($repositoryMock);

        $importMock = $this->getMock(Import::class, ['getAttribute']);
        $importMock->method('getAttribute')->willReturn(1);
        $file = ['basename' => '', 'path' => ''];

        $items = $this->importer->import($importMock, $file);

        $this->assertCount(1, $items);

        $expected = [
            'id' => 'CZE:MG.MGA_0-lomeni_s',
            'dating' => '1981',
            'date_earliest' => 1980,
            'date_latest' => 1982,
            'state_edition' => 'originál',
            'author' => 'autor',
            'title' => 'titul',
            'gallery' => 'Moravská galerie, MG',
            'identifier' => 'MGA 0/lomeni_s',
            'medium' => 'material, matný',
            'technique' => 'zmenšenina, černobílá',
            'relationship_type' => 'ze souboru',
            'work_type' => 'fotografie, pozitiv',
            'related_work' => 'Bienále Brno',
            'measurement' => 'šířka 4,6 cm; délka 6 cm; výška hlavní části 2 cm',
        ];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $items[0]->$key);
        }
    }
}