<?php

namespace Tests\Feature;

use App\Facades\Experiment;
use Tests\TestCase;

class RedirectLegacyCatalogRequestTest extends TestCase
{

    public function test_redirects_to_new_catalog_url(): void
    {
        $legacyQuery = [
            'author' => 'Vodrážka, Jaroslav',
            'work_type' => 'maliarstvo',
            'object_type' => 'závesný obraz',
            'tag' => 'architektúra',
            'topic' => 'architektonický motív',
            'technique' => 'olej',
            'medium' => 'drevo',
            'gallery' => 'Slovenská národná galéria, SNG',
            'has_image' => '1',
            'has_iip' => '1',
            'is_free' => '1',
            'has_text' => '1',
            'years_range' => '1533,1912',
            'color' => '38E619',
            'credit' => 'súkromná zbierka',
            'related_work' => 'B. Němcová, Babička, Bratislava 1965',
            'is_for_reproduction' => 'false',
            'contributor' => 'Hanáková, Petra',
            'search' => 'jaroslav',
            'sort_by' => 'created_at',
        ];

        $response = $this->get(route('frontend.catalog.index', $legacyQuery));

        $expectedQuery = [
            'filter' => [
                'author' => ['Vodrážka, Jaroslav'],
                'work_type' => ['maliarstvo'],
                'object_type' => ['závesný obraz'],
                'tag' => ['architektúra'],
                'topic' => ['architektonický motív'],
                'technique' => ['olej'],
                'medium' => ['drevo'],
                'gallery' => ['Slovenská národná galéria, SNG'],
                'has_image' => 'true',
                'has_iip' => 'true',
                'is_free' => 'true',
                'has_text' => 'true',
                'date_latest' => ['gte' => '1533'],
                'date_earliest' => ['lte' => '1912'],
                'color' => '38E619',
                'credit' => ['súkromná zbierka'],
                'related_work' => ['B. Němcová, Babička, Bratislava 1965'],
                'is_for_reproduction' => 'false',
                'contributor' => ['Hanáková, Petra'],
            ],
            'q' => 'jaroslav',
            'sort' => ['created_at' => 'desc'],
        ];

        ksort($expectedQuery['filter']);

        $response->assertStatus(302);
        $response->assertRedirectToRoute('frontend.catalog.index', $expectedQuery);
    }

    public function test_does_not_change_new_request_format(): void
    {
        $query = [
            'filter' => ['topic' => ['summer']],
        ];

        $this->get(route('frontend.catalog.index', $query))->assertOk();
    }
}
