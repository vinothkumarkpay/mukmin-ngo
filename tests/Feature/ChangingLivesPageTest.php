<?php

namespace Tests\Feature;

use Tests\TestCase;

class ChangingLivesPageTest extends TestCase
{
    public function test_changing_lives_page_loads_successfully()
    {
        $response = $this->get('/changing-lives');

        $response->assertStatus(200);
        $response->assertSee('Changing Lives');
        $response->assertSee('Advancing Communities');
        $response->assertSee('Through Meaningful Impact');
        $response->assertSee('Philanthropic Funds');
        $response->assertSee('Faith & Giving');
        $response->assertSee('Ecosystem Building Initiatives');
        $response->assertSee('ImpactCollab');
    }
}
