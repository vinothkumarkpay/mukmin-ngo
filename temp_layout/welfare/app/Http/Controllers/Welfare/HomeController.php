<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $iconBoxes = [
            ['title' => 'Mission', 'icon' => 'cmsms-icon-award-1', 'text' => 'Accusantium quam, ultricies eget tempor id, aliquam eget nibh et. Maecen aliquam, risus at semper ullamcorper, magna'],
            ['title' => 'Events', 'icon' => 'cmsms-icon-user-group', 'text' => 'Accusantium quam, ultricies eget tempor id, aliquam eget nibh et. Maecen aliquam, risus at semper ullamcorper, magna'],
            ['title' => 'Support', 'icon' => 'cmsms-icon-headphones-alt', 'text' => 'Accusantium quam, ultricies eget tempor id, aliquam eget nibh et. Maecen aliquam, risus at semper ullamcorper, magna'],
            ['title' => 'Volunteer', 'icon' => 'cmsms-icon-user-add-2', 'text' => 'Accusantium quam, ultricies eget tempor id, aliquam eget nibh et. Maecen aliquam, risus at semper ullamcorper, magna'],
        ];

        $featuredCampaign = [
            'title' => 'Help Children in Need',
            'excerpt' => 'Your support provides food, education, and healthcare to children in underserved communities around the world.',
            'raised' => 18400,
            'goal' => 25000,
            'image' => 'https://demo.welfare.cmsmasters.net/wp-content/uploads/sites/3/2015/04/photodune-11135304-charity-worker-collecting-sponsorship-from-man-in-street-s.jpg',
            'slug' => 'help-children-in-need',
        ];

        $campaigns = [
            ['title' => 'Clean Water Initiative', 'excerpt' => 'Fund filtration systems for rural schools.', 'raised' => 12000, 'goal' => 20000, 'image' => 'https://images.unsplash.com/photo-1548839140-29a7492991cf?w=580&q=80', 'slug' => 'clean-water'],
            ['title' => 'Emergency Food Relief', 'excerpt' => 'Nutritious meals for disaster-affected families.', 'raised' => 8500, 'goal' => 15000, 'image' => 'https://images.unsplash.com/photo-1488521787991-ed7b68cf0ff0?w=580&q=80', 'slug' => 'food-relief'],
            ['title' => 'Education for All', 'excerpt' => 'School supplies and scholarships for youth.', 'raised' => 22000, 'goal' => 30000, 'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=580&q=80', 'slug' => 'education'],
            ['title' => 'Medical Aid Drive', 'excerpt' => 'Essential medicines for rural clinics.', 'raised' => 7600, 'goal' => 12000, 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=580&q=80', 'slug' => 'medical-aid'],
        ];

        $posts = [
            ['title' => 'How Your Donation Changes Lives', 'excerpt' => 'Every contribution helps us reach more communities.', 'date' => '2026-03-15', 'image' => 'https://images.unsplash.com/photo-1469571486292-0ba58a3f068b?w=580&q=80', 'slug' => 'donation-changes-lives'],
            ['title' => 'Volunteer Spotlight', 'excerpt' => 'Meet the dedicated volunteers behind our programs.', 'date' => '2026-03-08', 'image' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=580&q=80', 'slug' => 'volunteer-spotlight'],
            ['title' => 'Annual Impact Report', 'excerpt' => 'A transparent look at outcomes we achieved together.', 'date' => '2026-02-28', 'image' => 'https://images.unsplash.com/photo-1532629345422-7515f0d054fb?w=580&q=80', 'slug' => 'impact-report'],
            ['title' => 'Community Outreach Update', 'excerpt' => 'Highlights from our latest field programs.', 'date' => '2026-02-15', 'image' => 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=580&q=80', 'slug' => 'outreach-update'],
        ];

        $donators = [
            ['name' => 'John Doe', 'amount' => 250, 'campaign' => 'Help Children in Need', 'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80'],
            ['name' => 'Jane Smith', 'amount' => 100, 'campaign' => 'Clean Water Initiative', 'image' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80'],
            ['name' => 'Robert Lee', 'amount' => 500, 'campaign' => 'Education for All', 'image' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=200&q=80'],
            ['name' => 'Maria Garcia', 'amount' => 75, 'campaign' => 'Medical Aid Drive', 'image' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80'],
        ];

        return view('welfare.pages.home', compact('iconBoxes', 'featuredCampaign', 'campaigns', 'posts', 'donators'));
    }
}
