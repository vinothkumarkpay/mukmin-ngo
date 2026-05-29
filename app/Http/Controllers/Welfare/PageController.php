<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about()
    {
        $coa = $this->resolveImages(config('welfare_team.coa', []), 'coa');
        $cec = $this->resolveImages(config('welfare_team.cec', []), 'cec');
        $exco = $this->resolveImages(config('welfare_team.exco', []), 'exco');
        $bureau = $this->resolveImages(config('welfare_team.bureau', []), 'bureau');

        return view('welfare.pages.about', compact('coa', 'cec', 'exco', 'bureau'));
    }

    public function whoWeAre()
    {
        return redirect()->to(route('welfare.about') . '#who-we-are');
    }

    public function presidentNote()
    {
        return redirect()->to(route('welfare.about') . '#president-note');
    }

    public function leadership()
    {
        return redirect()->to(route('welfare.about') . '#leadership');
    }

    private function resolveImages(array $members, string $category): array
    {
        return array_map(function ($member) use ($category) {
            $member['image'] = $this->getMemberImage($member['name'], $category);
            return $member;
        }, $members);
    }

    private function getMemberImage(string $name, string $category): string
    {
        $nameSlug = trim(preg_replace('/[^a-z0-9]+/i', '_', strtolower($name)), '_');
        $categorySlug = trim(preg_replace('/[^a-z0-9]+/i', '_', strtolower($category)), '_');

        $candidates = [
            "{$categorySlug}_{$nameSlug}",
            $nameSlug
        ];

        $extensions = ['jpg', 'png', 'jpeg', 'webp', 'svg'];

        foreach ($candidates as $candidate) {
            foreach ($extensions as $ext) {
                $relativePath = "welfare/img/leadership/{$candidate}.{$ext}";
                if (file_exists(public_path($relativePath))) {
                    return asset($relativePath);
                }
            }
        }

        return asset('welfare/img/leadership/default.svg');
    }

    public function ecosystem()
    {
        return view('welfare.pages.ecosystem');
    }

    public function serve()
    {
        return view('welfare.pages.serve');
    }

    public function impact()
    {
        return view('welfare.pages.impact');
    }

    public function mfls()
    {
        return view('welfare.pages.mfls');
    }

    public function sirat()
    {
        return view('welfare.pages.sirat');
    }

    public function news()
    {
        return view('welfare.pages.news', [
            'momentsGallery' => $this->buildMomentsGallery(),
        ]);
    }

    /**
     * Load Moments of MUKMIN images from configured public folders.
     */
    private function buildMomentsGallery(): array
    {
        $images = [];
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $basePath = public_path(config('welfare_gallery.moments_path'));

        foreach (config('welfare_gallery.categories', []) as $category) {
            $folderPath = $basePath . DIRECTORY_SEPARATOR . $category['folder'];

            if (! is_dir($folderPath)) {
                continue;
            }

            $files = scandir($folderPath);
            if ($files === false) {
                continue;
            }

            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $fullPath = $folderPath . DIRECTORY_SEPARATOR . $file;
                if (! is_file($fullPath)) {
                    continue;
                }

                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (! in_array($extension, $extensions, true)) {
                    continue;
                }

                $relative = config('welfare_gallery.moments_path')
                    . '/' . $category['folder']
                    . '/' . $file;

                $title = ucwords(str_replace(['-', '_'], ' ', pathinfo($file, PATHINFO_FILENAME)));

                $images[] = [
                    'src' => asset($relative),
                    'title' => $title,
                    'category' => $category['slug'],
                    'category_label' => $category['label'],
                ];
            }
        }

        usort($images, function ($a, $b) {
            return strcmp($a['title'], $b['title']);
        });

        return $images;
    }

    public function changing()
    {
        return view('welfare.pages.changing');
    }

    public function contact()
    {
        return view('welfare.pages.contact');
    }

    public function legalDisclaimer()
    {
        return view('welfare.pages.legal-disclaimer');
    }

    public function donate()
    {
        return view('welfare.pages.donate');
    }
}
