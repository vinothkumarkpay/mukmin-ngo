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
        
        // Assert some new COA members
        $response->assertSee('Prof. Tan Sri Datuk Dr. Haji Mohamed Haniffa Haji Abdullah');
        $response->assertSee('YA Dato’ Seri Vazeer Alam Mydin Meera');
        $response->assertSee('Datuk Seri Dr. Jahaberdeen Mohamed Yunoos');

        // Assert some new CEC members
        $response->assertSee('Datuk Wira Shahul Hameed Bin Shaik Dawood');
        $response->assertSee('Puan Fouziah Banu Binti Sultan Muhamad');
        $response->assertSee('Abdul');

        // Assert some new EXCO members
        $response->assertSee('Datuk Dr. Muhammad Ismail bin Abu Bakar');
        $response->assertSee('Datuk Haji Mohammed Mosin bin Abdul Razak');
        $response->assertSee('Dato’ Haji Faruk bin V. Raju Mohamed');

        // Assert some new Bureau Chairs
        $response->assertSee('Datuk Wira Naina Mohamed Bin Sultan Abdul Kadir');
        $response->assertSee('Dato\' (Is) Mohd Kassim Bin Aliah');
        $response->assertSee('Jaleel Abu Baker');
    }
}
