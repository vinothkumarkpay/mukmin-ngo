<?php

namespace Tests\Feature;

use Tests\TestCase;

class MflsPageTest extends TestCase
{
    public function test_mfls_page_loads_successfully()
    {
        $response = $this->get('/impact-areas/mfls');

        $response->assertStatus(200);
        $response->assertSee('MUKMIN Future Leaders Scholarship (MFLS)', false);
        $response->assertSee('A National Scholarship & Talent Development Programme', false);
        $response->assertSee('How It Works', false);
        $response->assertSee('Available Pathways', false);
        $response->assertSee('TVET (Skills & Technical Pathways)', false);
    }

    public function test_impact_page_read_more_links_to_mfls()
    {
        $response = $this->get('/impact-areas');

        $response->assertStatus(200);
        $response->assertSee(route('welfare.impact.mfls'), false);
    }
}
