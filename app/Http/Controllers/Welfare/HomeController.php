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
            ['title' => 'Education Aid', 'excerpt' => 'Providing educational support, learning access and development opportunities to empower future generations.', 'raised' => 12000, 'goal' => 20000, 'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=580&q=80', 'slug' => 'education-aid'],
            ['title' => 'Social Aid', 'excerpt' => 'Strengthening Communities Through Support. Delivering community assistance and outreach programmes.', 'raised' => 8500, 'goal' => 15000, 'image' => 'https://images.unsplash.com/photo-1488521787991-ed7b68cf0ff0?w=580&q=80', 'slug' => 'social-aid'],
            ['title' => 'Healthcare Aid', 'excerpt' => 'Advancing Community Well-Being. Improving access to healthcare support and wellness initiatives.', 'raised' => 22000, 'goal' => 30000, 'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=580&q=80', 'slug' => 'healthcare-aid'],
            ['title' => 'Others', 'excerpt' => 'Responding to Emerging Community Needs. Supporting strategic and community-driven initiatives.', 'raised' => 7600, 'goal' => 12000, 'image' => 'https://images.unsplash.com/photo-1532629345422-7515f0d054fb?w=580&q=80', 'slug' => 'other-aid'],
        ];

        $posts = [
            ['title' => 'MUKMIN Hari Raya Aidilfitri Open House 2025', 'excerpt' => 'MUKMIN’s Hari Raya Aidilfitri Open House gathered NGOs, mosques, madrasahs, suraus and tahfiz institutions from across Malaysia in a celebration of unity and community spirit, welcoming approximately 2,000 guests including more than 200 religious scholars.', 'date' => '2025-04-12', 'image' => 'https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?w=580&q=80', 'slug' => 'hari-raya-2025'],
            ['title' => 'SIRAT Leaders Forum', 'excerpt' => 'The SIRAT Leaders Forum convened 250 NGO leaders, policymakers, professionals and religious institutions in a strategic platform focused on collaboration, leadership and sustainable community development.', 'date' => '2025-08-29', 'image' => 'https://images.unsplash.com/photo-1540317580384-e5d43616b9aa?w=580&q=80', 'slug' => 'sirat-leaders-forum'],
            ['title' => 'Official Launch of FIKRAH', 'excerpt' => 'FIKRAH was officially launched as MUKMIN’s strategic think tank focused on innovation, policy thinking, research and long-term community development.', 'date' => '2025-11-22', 'image' => 'https://images.unsplash.com/photo-1505373877841-8d25f7d46678?w=580&q=80', 'slug' => 'launch-fikrah'],
            ['title' => 'SIRAT Youth Summit & Youth Icon Awards', 'excerpt' => 'Malaysia’s largest community youth gatherings, the programme brought together over 1,000 changemakers through leadership sessions, panel discussions, pitching sessions and the Youth Icon Awards.', 'date' => '2025-11-22', 'image' => 'https://images.unsplash.com/photo-1523580494112-071d283626e6?w=580&q=80', 'slug' => 'youth-summit'],
        ];

        return view('welfare.pages.home', compact('iconBoxes', 'featuredCampaign', 'campaigns', 'posts'));
    }
}
