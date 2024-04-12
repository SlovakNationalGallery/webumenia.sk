<?php

namespace Tests\BrowserKit\Admin;

use App\Import;
use App\Importers\MgImporter;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\BrowserKitTestCase;

class ImportFormTest extends BrowserKitTestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['can_administer' => true]);
        $this->actingAs($this->user);
    }

    public function testCreate()
    {
        $this->visit('/imports/create')
            ->type('name', 'name')
            ->type(MgImporter::class, 'class_name')
            ->press('Uložiť')
            ->seePageIs('/imports');
    }

    public function testEdit()
    {
        $item = Import::factory()->create();

        $this->visit(sprintf('/imports/%s/edit', $item->id))
            ->press('Uložiť')
            ->seePageIs('/imports');
    }
}
