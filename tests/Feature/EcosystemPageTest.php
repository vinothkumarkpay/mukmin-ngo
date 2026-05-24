<?php

namespace Tests\Feature;

use Tests\TestCase;

class EcosystemPageTest extends TestCase
{
    public function test_ecosystem_page_loads_successfully()
    {
        $response = $this->get('/ecosystem');

        $response->assertStatus(200);
        $response->assertSee('One Ecosystem.');
        $response->assertSee('Three Engines of Impact.');
        $response->assertSee('FIKRAH');
        $response->assertSee('MUKMIN');
        $response->assertSee('Yayasan MUKMIN');
    }
}
