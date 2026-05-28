<?php

namespace Tests\Feature;

use Tests\TestCase;

class LeadershipPageTest extends TestCase
{
    public function test_leadership_page_loads_successfully()
    {
        $response = $this->get('/about/leadership');

        $response->assertStatus(200);
        $response->assertSee('Leadership & Governance');
        
        // Assert some new COA members
        $response->assertSee('Prof. Tan Sri Datuk Dr. Haji Mohamed Haniffa bin Haji Abdullah');
        $response->assertSee('YA Dato\' Seri Vazeer Alam Mydin Meera');
        $response->assertSee('Tuan Rafee Haneef');

        // Assert some new CEC members
        $response->assertSee('Datuk Wira Shahul Hameed Bin Shaik Dawood');
        $response->assertSee('Puan Fouziah Banu Binti Sultan Muhamad');
        $response->assertSee('Puan Nur Aisyah Binti Said Ali');

        // Assert some new EXCO members
        $response->assertSee('Faruk Bin V Raju Mohamed');
        $response->assertSee('Datuk Haji Mohammed Mosin');
        $response->assertSee('Muhammad Ismail bin Abu Bakar');

        // Assert some new Bureau Chairs
        $response->assertSee('Datuk Wira Naina Mohamed Bin Sultan Abdul Kadir');
        $response->assertSee('Dato\' (Is) Mohd Kassim Bin Aliah');
        $response->assertSee('Datuk Jake Abdullah');
    }
}
