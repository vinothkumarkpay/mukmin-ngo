<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about()
    {
        return redirect()->route('welfare.about.who-we-are');
    }

    public function whoWeAre()
    {
        return view('welfare.pages.about.who-we-are');
    }

    public function presidentNote()
    {
        return view('welfare.pages.about.president-note');
    }

    public function leadership()
    {
        $coa = $this->resolveImages(config('welfare_team.coa', []), 'coa');
        $cec = $this->resolveImages(config('welfare_team.cec', []), 'cec');
        $exco = $this->resolveImages(config('welfare_team.exco', []), 'exco');
        $bureau = $this->resolveImages(config('welfare_team.bureau', []), 'bureau');

        return view('welfare.pages.about.leadership', compact('coa', 'cec', 'exco', 'bureau'));
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

    public function news()
    {
        return view('welfare.pages.news');
    }

    public function changing()
    {
        return view('welfare.pages.changing');
    }

    public function contact()
    {
        return view('welfare.pages.contact');
    }

    public function donate()
    {
        return view('welfare.pages.donate');
    }
}
