<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about()
    {
        $coa = config('welfare_team.coa', []);
        $cec = config('welfare_team.cec', []);
        $exco = config('welfare_team.exco', []);
        $bureau = config('welfare_team.bureau', []);

        return view('welfare.pages.about', compact('coa', 'cec', 'exco', 'bureau'));
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
