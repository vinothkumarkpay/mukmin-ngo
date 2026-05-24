<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;

class CampaignController extends Controller
{
    public function index()
    {
        $campaigns = [
            [
                'title' => 'Clean Water for Rural Schools',
                'excerpt' => 'Help us install filtration systems so children have safe drinking water every day.',
                'raised' => 18400,
                'goal' => 25000,
                'image' => 'https://images.unsplash.com/photo-1548839140-29a7492991cf?w=800&q=80',
                'slug' => 'clean-water-schools',
            ],
            [
                'title' => 'Emergency Food Relief',
                'excerpt' => 'Provide nutritious meals to families affected by natural disasters.',
                'raised' => 9200,
                'goal' => 15000,
                'image' => 'https://images.unsplash.com/photo-1488521787991-ed7b68cf0ff0?w=800&q=80',
                'slug' => 'emergency-food-relief',
            ],
            [
                'title' => 'Education for Every Child',
                'excerpt' => 'Fund school supplies, uniforms, and scholarships for underserved youth.',
                'raised' => 31200,
                'goal' => 40000,
                'image' => 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&q=80',
                'slug' => 'education-every-child',
            ],
            [
                'title' => 'Medical Supplies Drive',
                'excerpt' => 'Equip rural clinics with essential medicines and diagnostic tools.',
                'raised' => 7600,
                'goal' => 12000,
                'image' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?w=800&q=80',
                'slug' => 'medical-supplies-drive',
            ],
        ];

        return view('welfare.pages.campaigns.index', compact('campaigns'));
    }

    public function show(string $slug)
    {
        $campaign = [
            'title' => 'Clean Water for Rural Schools',
            'content' => '<p>Access to clean water is a fundamental human right. This campaign funds filtration systems, maintenance training, and hygiene education in rural schools.</p><p>With your help, we can reach 15 schools in the first phase and expand from there.</p>',
            'raised' => 18400,
            'goal' => 25000,
            'image' => 'https://images.unsplash.com/photo-1548839140-29a7492991cf?w=1200&q=80',
            'slug' => $slug,
        ];

        return view('welfare.pages.campaigns.show', compact('campaign'));
    }
}
