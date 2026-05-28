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
        $response->assertSee('How It Works', false);
        $response->assertSee('Available Pathways', false);
        $response->assertSee('Programme Snapshot', false);
        $response->assertSee('Further details on MFLS will be shared soon', false);
    }

    public function test_impact_page_read_more_links_to_mfls()
    {
        $response = $this->get('/impact-areas');

        $response->assertStatus(200);
        $response->assertSee(route('welfare.impact.mfls'), false);
    }
}
