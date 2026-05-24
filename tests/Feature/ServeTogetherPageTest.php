<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServeTogetherPageTest extends TestCase
{
    public function test_serve_together_page_loads_successfully()
    {
        $response = $this->get('/serve-together');

        $response->assertStatus(200);
        $response->assertSee('Join Our Ecosystem');
        $response->assertSee('Strengthening Communities Through Collective Action');
        $response->assertSee('Measuring Progress. Empowering Communities.');
        $response->assertSee('Membership Categories');
        $response->assertSee('Ordinary Members');
        $response->assertSee('Friends of MUKMIN');
        $response->assertSee('Membership Process');
        $response->assertSee('Why Join MUKMIN?');
        $response->assertSee('Share Your Ideas');
        $response->assertSee('Volunteer Now');
        $response->assertSee('Be A Mentor');
        $response->assertSee('Partner With Us');
    }
}
