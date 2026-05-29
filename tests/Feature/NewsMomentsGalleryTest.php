<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class NewsMomentsGalleryTest extends TestCase
{
    protected function tearDown(): void
    {
        $testDir = public_path('welfare/img/moments/Kembara Ramadhan MUKMIN');
        $testFile = $testDir . '/test-gallery.jpg';

        if (File::exists($testFile)) {
            File::delete($testFile);
        }

        parent::tearDown();
    }

    public function test_news_page_shows_moments_gallery_filters()
    {
        $response = $this->get('/news');

        $response->assertStatus(200);
        $response->assertSee('Moments of MUKMIN', false);
        $response->assertSee('All Photos', false);
        $response->assertSee('Kembara Ramadhan MUKMIN', false);
        $response->assertSee('SIRAT Leaders Forum', false);
        $response->assertSee('FIKRAH Launch', false);
        $response->assertDontSee('Ramadhan Aid', false);
        $response->assertDontSee('Forums &amp; Summits', false);
    }

    public function test_moments_gallery_loads_images_from_folder()
    {
        $testDir = public_path('welfare/img/moments/Kembara Ramadhan MUKMIN');
        File::ensureDirectoryExists($testDir);
        File::put($testDir . '/test-gallery.jpg', 'test');

        $response = $this->get('/news');

        $response->assertStatus(200);
        $response->assertSee('welfare/img/moments/Kembara Ramadhan MUKMIN/test-gallery.jpg', false);
        $response->assertSee('Test Gallery', false);
    }
}
