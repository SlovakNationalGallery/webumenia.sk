<?php

namespace Tests\Harvest\Importers;

use App\Authority;
use App\Harvest\Importers\MuseionItemImporter;
use App\Harvest\Mappers\GmuhkItemMapper;
use App\Harvest\Progress;
use App\Matchers\AuthorityMatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Harvest\FakeRecordFactory;
use Tests\TestCase;
use Tests\WithoutSearchIndexing;

class MuseionItemImporterTest extends TestCase
{
    private MuseionItemImporter $importer;

    use RefreshDatabase;
    use WithoutSearchIndexing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->importer = new MuseionItemImporter(
            app(GmuhkItemMapper::class),
            app(AuthorityMatcher::class)
        );
    }

    public function testParseRoleFromArtistName()
    {
        Authority::factory()->create([
            'name' => 'Miroslav Podhrázský',
            'birth_year' => null,
            'death_year' => null,
        ]);

        $row = FakeRecordFactory::buildGmuhkItem([
            'author' => ['Miroslav Podhrázský (fotograf)'],
        ]);
        $item = $this->importer->import($row, new Progress());
        $item->refresh();
        $this->assertEquals('fotograf', $item->authorities[0]->pivot->role);
    }
}
