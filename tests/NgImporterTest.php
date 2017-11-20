<?php

namespace Tests;

use App\Collection;
use App\Import;
use App\Importers\NgImporter;
use App\Repositories\CsvRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class NgImporterTest extends TestCase
{
    use DatabaseMigrations;

    public function testImport() {
        $collection = new Collection();
        $collection->name = 'Veletržní palác';
        $collection->type = 'type';
        $collection->order = 1;
        $collection->save();

        $data = [
            'Inventární čís.' => 'Vm 1168',
            'Značeno (jak např. letopočet, signatura, monogram)' => 'Podpis: Namaloval Čchi Chuang. Pečeť: Starý Paj.',
            'značeno kde (umístění v díle)' => 'vlevo nahoře',
            'Autor (jméno příjmení, příp. Anonym)' => 'Prezývka, vlastným menom Meno',
            'Autor 2' => 'Ďalší autor, autorka',
            'Autor 3' => 'Tretí autor',
            'Čistý rozměr (bez rámu, pasparty apod)' => '',
            'šířka' => '1',
            'výška' => '2',
            'hloubka' => '3',
            'jednotky' => 'cm',
            'šířka_0' => '4',
            'výška_0' => '5',
            'hloubka_0' => '6',
            'jednotky_0' => 'm',
            'Rozměr 2' => 'druhý rozmer',
            'popis rozměru (např. s rámem, se soklem, celý papír apod.)' => 'celkovo',
            'Datování (určené)' => '1998 - 2003',
            'Kolekce (budova)' => 'Veletržní palác',
            'Sbírka' => 'SGK',
        ];
        $records = new \ArrayIterator([$data]);

        $repositoryMock = $this->getMock(CsvRepository::class);
        $repositoryMock->method('getFiltered')->willReturn($records);

        $this->importer = new NgImporter($repositoryMock);

        $importMock = $this->getMock(Import::class, ['getAttribute']);
        $importMock->method('getAttribute')->willReturn(1);
        $file = ['basename' => '', 'path' => ''];

        $items = $this->importer->import($importMock, $file);

        $this->assertCount(1, $items);

        $expected = [
            'id' => 'CZE:NG.Vm_1168',
            'identifier' => 'Vm 1168',
            'inscription' => 'vlevo nahoře: Podpis: Namaloval Čchi Chuang. Pečeť: Starý Paj.',
            'author' => 'Prezývka (vlastným menom Meno); Ďalší autor (autorka); Tretí autor',
            'measurement' => 'šířka 1 cm; výška 2 cm; hloubka 3 cm',
            'date_earliest' => 1998,
            'date_latest' => 2003,
            'gallery_collection' => 'Sbírka grafiky a kresby',
        ];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $items[0]->$key);
        }

        $this->assertCount(1, $items[0]->collections);
        $this->assertEquals($collection->name, $items[0]->collections[0]->name);
    }
}