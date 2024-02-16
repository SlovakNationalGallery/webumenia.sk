<?php

namespace Tests\Views\Frontend;

use App\Facades\Experiment;
use Tests\TestCase;

class NewCatalogTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Experiment::set('new-catalog');
    }

    public function test_title_is_based_on_filters()
    {
        $filters = [
            'filter[author]' => ['Mednyánszky, Ladislav', 'Giambologna'],
            'filter[date_latest][gte]' => '1651',
            'filter[date_earliest][lte]' => '1932',
        ];

        $this->get(route('frontend.catalog.index', $filters))->assertSeeText(
            'autor: Ladislav Mednyánszky, Giambologna • v rokoch 1651 — 1932 |'
        );

        $this->get(route('api.v1.items.catalog-title', $filters))->assertSeeText(
            'autor: Ladislav Mednyánszky, Giambologna • v rokoch 1651 — 1932'
        );
    }

    public function test_title_works_with_authority_ids()
    {
        $this->markTestIncomplete('Waiting for WEBUMENIA-2005');
    }
}
