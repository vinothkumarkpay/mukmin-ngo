<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about()
    {
        $team = [
            ['name' => 'Sarah Mitchell', 'role' => 'Executive Director', 'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80'],
            ['name' => 'James Chen', 'role' => 'Programs Lead', 'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=400&q=80'],
            ['name' => 'Amira Hassan', 'role' => 'Community Outreach', 'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&q=80'],
        ];

        return view('welfare.pages.about', compact('team'));
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
