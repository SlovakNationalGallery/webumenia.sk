<?php

namespace Tests\Feature;

use App\Redirect;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class DatabaseRedirectorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_it_will_redirect_for_enabled_rules()
    {
        $redirect = Redirect::factory()->create();

        $this->get($redirect->source_url)
            ->assertStatus(Response::HTTP_FOUND)
            ->assertRedirect($redirect->target_url);
    }
}
