<?php

namespace Tests\Feature;

use Tests\TestCase;

class LeadershipPageTest extends TestCase
{
    public function test_leadership_page_loads_successfully()
    {
        $response = $this->followingRedirects()->get('/about/leadership');

        $response->assertStatus(200);
        $response->assertSee('Leadership & Governance');
        
        // Assert COA members
        $response->assertSee("YA Dato' Seri Vazeer Alam Mydin Meera");
        $response->assertSee('Datuk Seri Dr. Jahaberdeen Mohamed Yunoos');
        $response->assertSee('Tuan Syed Ali Shahul Hameed');

        // Assert some new CEC members
        $response->assertSee('Datuk Wira Shahul Dawood');
        $response->assertSee('Puan Fouziah Banu Binti Sultan Muhamad');
        $response->assertSee('Abdul');

        // Assert EXCO members
        $response->assertSee("Dato' Abdul Hamid PV Abdu");
        $response->assertSee('Datuk Hj Mohammed Mosin Abdul Razak');
        $response->assertSee('Datuk Dr Muhammad Ismail Abu Bakar');

        // Assert some new Bureau Chairs
        $response->assertSee('Datuk Wira Naina Mohamed Bin Sultan Abdul Kadir');
        $response->assertSee('Mohd Kassim Bin Aliah', false);
        $response->assertSee('Tuan Jaleeludeen Bin Abu Baker');
    }
}
