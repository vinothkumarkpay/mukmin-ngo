<?php

namespace Tests\Feature;

use Tests\TestCase;

class ComingSoonPageTest extends TestCase
{
    public function test_coming_soon_route_loads_successfully()
    {
        $response = $this->get('/coming-soon');

        $response->assertStatus(200);
        $response->assertSee('Coming Soon');
        $response->assertSee('A National Ecosystem for Community Transformation');
        $response->assertSee('support@mukmin.org');
        $response->assertSee('mukmin_logo.png');
    }

    public function test_static_coming_soon_html_is_available()
    {
        $this->assertFileExists(public_path('coming-soon.html'));
    }
}
