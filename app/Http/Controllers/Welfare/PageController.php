<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use Illuminate\Support\Str;

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
        $moments = $this->buildMomentsGallery();

        return view('welfare.pages.news', [
            'momentsCategories' => $moments['categories'],
            'momentsGallery' => $moments['images'],
        ]);
    }

    /**
     * Load Moments of MUKMIN images from public/welfare/img/moments subfolders.
     * Each subfolder name becomes a gallery filter category.
     */
    private function buildMomentsGallery(): array
    {
        $categories = [];
        $images = [];
        $extensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        $basePath = public_path(config('welfare_gallery.moments_path'));

        if (! is_dir($basePath)) {
            return ['categories' => [], 'images' => []];
        }

        $folders = scandir($basePath);
        if ($folders === false) {
            return ['categories' => [], 'images' => []];
        }

        foreach ($folders as $folder) {
            if ($folder === '.' || $folder === '..') {
                continue;
            }

            $folderPath = $basePath . DIRECTORY_SEPARATOR . $folder;
            if (! is_dir($folderPath)) {
                continue;
            }

            $slug = Str::slug($folder);
            $category = [
                'folder' => $folder,
                'slug' => $slug,
                'label' => $folder,
            ];
            $categories[] = $category;

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
                    . '/' . $folder
                    . '/' . $file;

                $images[] = [
                    'src' => asset($relative),
                    'title' => $folder,
                    'file' => $file,
                    'category' => $slug,
                    'category_label' => $folder,
                ];
            }
        }

        usort($categories, fn ($a, $b) => strcasecmp($a['label'], $b['label']));

        usort($images, function ($a, $b) {
            $categoryCompare = strcasecmp($a['category_label'], $b['category_label']);
            if ($categoryCompare !== 0) {
                return $categoryCompare;
            }

            return strnatcasecmp($a['file'], $b['file']);
        });

        return [
            'categories' => $categories,
            'images' => $images,
        ];
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

    public function comingSoon()
    {
        return view('welfare.pages.coming-soon');
    }
}
