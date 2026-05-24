<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $posts = [
            [
                'title' => 'How Your Donation Changes Lives',
                'excerpt' => 'Every contribution helps us reach more communities with essential services.',
                'date' => '2026-03-15',
                'author' => 'Sarah Mitchell',
                'image' => 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=800&q=80',
                'slug' => 'how-your-donation-changes-lives',
            ],
            [
                'title' => 'Volunteer Spotlight: Meet Our Field Team',
                'excerpt' => 'Dedicated volunteers are the backbone of our relief programs worldwide.',
                'date' => '2026-03-08',
                'author' => 'James Chen',
                'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=800&q=80',
                'slug' => 'volunteer-spotlight',
            ],
            [
                'title' => 'Annual Impact Report 2025',
                'excerpt' => 'A transparent look at where funds went and the outcomes we achieved together.',
                'date' => '2026-02-28',
                'author' => 'Amira Hassan',
                'image' => 'https://images.unsplash.com/photo-1532629345422-7515f0d054fb?w=800&q=80',
                'slug' => 'annual-impact-report-2025',
            ],
        ];

        return view('welfare.pages.blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = [
            'title' => 'How Your Donation Changes Lives',
            'content' => '<p>When you give to Welfare NGO, your support flows directly into programs that matter: clean water, education, emergency relief, and long-term community development.</p><p>We believe in transparency. That is why we publish regular updates and impact metrics so you can see the difference you make.</p>',
            'date' => '2026-03-15',
            'author' => 'Sarah Mitchell',
            'image' => 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=1200&q=80',
            'slug' => $slug,
        ];

        return view('welfare.pages.blog.show', compact('post'));
    }
}
