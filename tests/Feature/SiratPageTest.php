<?php

namespace Tests\Feature;

use Tests\TestCase;

class SiratPageTest extends TestCase
{
    public function test_sirat_page_loads_successfully()
    {
        $response = $this->get('/impact-areas/sirat-series');

        $response->assertStatus(200);
        $response->assertSee('SIRAT Series', false);
        $response->assertSee('The SIRAT Platforms', false);
        $response->assertSee('SIRAT Leaders Forum', false);
        $response->assertSee('SIRAT Youth Summit', false);
        $response->assertSee('SIRAT Global Forum 2026', false);
    }

    public function test_impact_page_read_more_links_to_sirat()
    {
        $response = $this->get('/impact-areas');

        $response->assertStatus(200);
        $response->assertSee(route('welfare.impact.sirat'), false);
    }
}
