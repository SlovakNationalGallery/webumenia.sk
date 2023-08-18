<?php

namespace Tests\Unit;

use App\Http\Middleware\RedirectLegacyCatalogRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Tests\TestCase;

class RedirectLegacyCatalogRequestTest extends TestCase
{
    // TODO move this into a 'feature test' and test the whole request
    public function test_redirects_to_new_catalog_url(): void
    {
        $middleware = new RedirectLegacyCatalogRequest();
        $legacyQuery = http_build_query([
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
            'sort_by' => 'created_at',
        ]);
        $request = Request::create("/katalog?$legacyQuery");

        $response = $middleware->handle($request, function () {});

        $expectedQuery = http_build_query([
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
                'late_latest' => ['gte' => '1533'],
                'date_earliest' => ['lte' => '1912'],
                'color' => '38E619',
                'sort' => ['created_at' => 'asc'],
            ],
        ]);

        $this->assertEquals($response->getStatusCode(), 302);
        $this->assertEquals(
            $response->headers->get('location'),
            $request->url() . '?' . $expectedQuery
        );
    }

    public function test_ignores_new_request_format(): void
    {
        $middleware = new RedirectLegacyCatalogRequest();
        $query = http_build_query([
            'filter' => ['topic' => ['summer']],
        ]);
        $request = Request::create("/katalog?$query");

        $response = $middleware->handle($request, fn() => new Response());

        $this->assertEquals($response->getStatusCode(), 200);
    }
}
