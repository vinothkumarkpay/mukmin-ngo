<?php

// A helper to generate Unsplash URLs for portraits easily
if (!function_exists('get_portrait_url')) {
    function get_portrait_url($index) {
        $ids = [
            'photo-1573496359142-b8d87734a5a2', // Woman 1
            'photo-1507003211169-0a1dd7228f2d', // Man 1
            'photo-1500648767791-00dcc994a43e', // Man 2
            'photo-1534528741775-53994a69daeb', // Woman 2
            'photo-1580489944761-15a19d654956', // Woman 3
            'photo-1519085360753-af0119f7cbe7', // Man 3
            'photo-1560250097-0b93528c311a', // Man 4
            'photo-1472099645785-5658abf4ff4e', // Man 5
            'photo-1573497019940-1c28c88b4f3e', // Woman 4
            'photo-1537368910025-700350fe46c7', // Man 6
            'photo-1544005313-94ddf0286df2', // Woman 5
            'photo-1501196354995-cbb51c65aaea', // Man 7
            'photo-1438761681033-6461ffad8d80', // Woman 6
            'photo-1506794778202-cad84cf45f1d', // Man 8
            'photo-1551836022-d5d88e9218df', // Woman 7
            'photo-1517841905240-472988babdf9', // Woman 8
            'photo-1492562080023-ab3db95bfbce', // Man 9
            'photo-1489980508314-941910ded1f4', // Man 10
            'photo-1551836022-b5b3b1c81670', // Woman 9
            'photo-1522075469751-3a6694fb2f61', // Man 11
        ];
        
        $id = $ids[$index % count($ids)];
        return "https://images.unsplash.com/{$id}?auto=format&fit=crop&w=300&h=300&q=80";
    }
}


return [
    /*
    |--------------------------------------------------------------------------
    | Council of Advisors (COA) - 11 members
    |--------------------------------------------------------------------------
    */
    'coa' => [
        [
            'name' => 'Tan Sri Dato\' Seri Dr. Abdul Rahman Ibrahim',
            'role' => 'Chairman / Senior Advisor',
            'org' => 'Former Chief Secretary to the Government',
            'image' => get_portrait_url(0)
        ],
        [
            'name' => 'Puan Sri Dr. Hajah Aminah Hashim',
            'role' => 'Senior Advisor',
            'org' => 'Professor Emeritus, Universiti Malaya',
            'image' => get_portrait_url(1)
        ],
        [
            'name' => 'Dato\' Sri Muhammad Rizal Tan',
            'role' => 'Corporate Advisor',
            'org' => 'Managing Director, Kencana Holdings',
            'image' => get_portrait_url(2)
        ],
        [
            'name' => 'Prof. Dr. Syed Muhammad Naquib',
            'role' => 'Academic & Islamic Studies Advisor',
            'org' => 'Institute of Islamic Thought & Civilization',
            'image' => get_portrait_url(3)
        ],
        [
            'name' => 'Datu Haji Ahmad Al-Hadi',
            'role' => 'Regional Advisor (East Malaysia)',
            'org' => 'Former Mufti of Sarawak',
            'image' => get_portrait_url(4)
        ],
        [
            'name' => 'Dr. Elizabeth Wong',
            'role' => 'Social Policy & Welfare Advisor',
            'org' => 'Malaysian Social Research Institute',
            'image' => get_portrait_url(5)
        ],
        [
            'name' => 'Dato\' Kamaruddin Ismail',
            'role' => 'Economic & Trade Advisor',
            'org' => 'President, Malaysian Chamber of Commerce',
            'image' => get_portrait_url(6)
        ],
        [
            'name' => 'Tan Sri Mohd Nasir Yasin',
            'role' => 'Strategic & Waqf Advisor',
            'org' => 'Chairman, National Waqf Foundation',
            'image' => get_portrait_url(7)
        ],
        [
            'name' => 'Puan Siti Sarah Marican',
            'role' => 'Legal & Governance Advisor',
            'org' => 'Senior Partner, Marican & Associates',
            'image' => get_portrait_url(8)
        ],
        [
            'name' => 'Ir. Dr. Chen Kok Wai',
            'role' => 'Infrastructure & Development Advisor',
            'org' => 'President, Institution of Engineers Malaysia',
            'image' => get_portrait_url(9)
        ],
        [
            'name' => 'Dato\' Dr. Haron Din Ahmad',
            'role' => 'Community Integration Advisor',
            'org' => 'Trustee, National Welfare Foundation',
            'image' => get_portrait_url(10)
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Central Executive Committee (CEC) - 15 members
    |--------------------------------------------------------------------------
    */
    'cec' => [
        [
            'name' => 'Datuk Dr. Mohd Gazali Haji Mohd Abas',
            'role' => 'President',
            'org' => 'MUKMIN National President',
            'image' => get_portrait_url(11)
        ],
        [
            'name' => 'Tuan Haji Mohd Shukri bin Haji Abdul Hamid',
            'role' => 'Deputy President 1',
            'org' => 'Strategic Operations & Alliances',
            'image' => get_portrait_url(12)
        ],
        [
            'name' => 'Dr. Hajah Nor Asiah binti Muhamad',
            'role' => 'Deputy President 2',
            'org' => 'Policy & Community Engagement',
            'image' => get_portrait_url(13)
        ],
        [
            'name' => 'Encik Ahmad Fauzi bin Azmi',
            'role' => 'Vice President 1',
            'org' => 'Education & Training Portfolio',
            'image' => get_portrait_url(14)
        ],
        [
            'name' => 'Puan Nurul Huda binti Ahmad',
            'role' => 'Vice President 2',
            'org' => 'Women & Family Development',
            'image' => get_portrait_url(15)
        ],
        [
            'name' => 'Dr. Tan Seng Giaw',
            'role' => 'Vice President 3',
            'org' => 'Health & Medical Services',
            'image' => get_portrait_url(16)
        ],
        [
            'name' => 'Haji Ibrahim bin Mohd Ali',
            'role' => 'Vice President 4',
            'org' => 'Economic Mobility & Waqf',
            'image' => get_portrait_url(17)
        ],
        [
            'name' => 'Puan Fatimah binti Awang',
            'role' => 'Vice President 5',
            'org' => 'East Malaysia Liaison & Welfare',
            'image' => get_portrait_url(18)
        ],
        [
            'name' => 'Encik Muhammad Zaki bin Ramli',
            'role' => 'Secretary-General',
            'org' => 'Administration & Secretarial Office',
            'image' => get_portrait_url(19)
        ],
        [
            'name' => 'Puan Rosnah binti Abdul Majid',
            'role' => 'Deputy Secretary-General 1',
            'org' => 'Documentation & Membership Affairs',
            'image' => get_portrait_url(0)
        ],
        [
            'name' => 'Encik Lim Kah Seng',
            'role' => 'Deputy Secretary-General 2',
            'org' => 'Ecosystem Partnerships & Logistics',
            'image' => get_portrait_url(1)
        ],
        [
            'name' => 'Haji Abdul Rahman bin Yusof',
            'role' => 'Honorary Treasurer',
            'org' => 'Finance & Treasury Management',
            'image' => get_portrait_url(2)
        ],
        [
            'name' => 'Puan Lee Siew Min',
            'role' => 'Deputy Honorary Treasurer',
            'org' => 'Audits & Compliance Reporting',
            'image' => get_portrait_url(3)
        ],
        [
            'name' => 'Encik Khairul Anuar bin Mansor',
            'role' => 'Chief of Information & Communications',
            'org' => 'Media Relations & Public Affairs',
            'image' => get_portrait_url(4)
        ],
        [
            'name' => 'Puan Norhafizah binti Husin',
            'role' => 'Deputy Chief of Information & Communications',
            'org' => 'Digital Content & Campaigns',
            'image' => get_portrait_url(5)
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Executive Committee (EXCO) - 45 members
    |--------------------------------------------------------------------------
    */
    'exco' => [
        [
            'name' => 'Dr. Azmin bin Mansor',
            'role' => 'EXCO Member',
            'tag' => 'Education & Training',
            'image' => get_portrait_url(6)
        ],
        [
            'name' => 'Puan Zaleha binti Abu Bakar',
            'role' => 'EXCO Member',
            'tag' => 'Welfare & Community Aid',
            'image' => get_portrait_url(7)
        ],
        [
            'name' => 'Encik Fahmi bin Rizal',
            'role' => 'EXCO Member',
            'tag' => 'Youth & Sports',
            'image' => get_portrait_url(8)
        ],
        [
            'name' => 'Dr. Rachel Wong Siew Lin',
            'role' => 'EXCO Member',
            'tag' => 'Health & Well-being',
            'image' => get_portrait_url(9)
        ],
        [
            'name' => 'Tuan Haji Mustafa bin Isa',
            'role' => 'EXCO Member',
            'tag' => 'Economic Mobility',
            'image' => get_portrait_url(10)
        ],
        [
            'name' => 'Puan Kartini binti Rahman',
            'role' => 'EXCO Member',
            'tag' => 'Women\'s Empowerment',
            'image' => get_portrait_url(11)
        ],
        [
            'name' => 'Encik Muthusamy a/l Krishnan',
            'role' => 'EXCO Member',
            'tag' => 'Social Integration',
            'image' => get_portrait_url(12)
        ],
        [
            'name' => 'Ustaz Roslan bin Mohamad',
            'role' => 'EXCO Member',
            'tag' => 'Religious Affairs',
            'image' => get_portrait_url(13)
        ],
        [
            'name' => 'Puan Shanti a/p Subramaniam',
            'role' => 'EXCO Member',
            'tag' => 'Education & Training',
            'image' => get_portrait_url(14)
        ],
        [
            'name' => 'Encik Alvin Tan Kok Keong',
            'role' => 'EXCO Member',
            'tag' => 'Digital & IT Systems',
            'image' => get_portrait_url(15)
        ],
        [
            'name' => 'Cik Norazalina binti Yusof',
            'role' => 'EXCO Member',
            'tag' => 'Media & PR Campaigns',
            'image' => get_portrait_url(16)
        ],
        [
            'name' => 'Dr. Muhammad Hafiz bin Salleh',
            'role' => 'EXCO Member',
            'tag' => 'Research & Publications',
            'image' => get_portrait_url(17)
        ],
        [
            'name' => 'Tuan Haji Omar bin Bakar',
            'role' => 'EXCO Member',
            'tag' => 'Welfare & Community Aid',
            'image' => get_portrait_url(18)
        ],
        [
            'name' => 'Puan Hajah Aminah binti Kassim',
            'role' => 'EXCO Member',
            'tag' => 'Women\'s Empowerment',
            'image' => get_portrait_url(19)
        ],
        [
            'name' => 'Encik Ramesh a/l Veerappan',
            'role' => 'EXCO Member',
            'tag' => 'Youth & Sports',
            'image' => get_portrait_url(0)
        ],
        [
            'name' => 'Cik Sarah binti Abdullah',
            'role' => 'EXCO Member',
            'tag' => 'Volunteer Mobilisation',
            'image' => get_portrait_url(1)
        ],
        [
            'name' => 'Encik Chong Wei Lung',
            'role' => 'EXCO Member',
            'tag' => 'Corporate Partnership',
            'image' => get_portrait_url(2)
        ],
        [
            'name' => 'Ustaz Ahmad Tirmizi bin Din',
            'role' => 'EXCO Member',
            'tag' => 'Religious Affairs',
            'image' => get_portrait_url(3)
        ],
        [
            'name' => 'Dr. Aishah binti Jamil',
            'role' => 'EXCO Member',
            'tag' => 'Health & Well-being',
            'image' => get_portrait_url(4)
        ],
        [
            'name' => 'Tuan Haji Zamri bin Nor',
            'role' => 'EXCO Member',
            'tag' => 'Economic Mobility',
            'image' => get_portrait_url(5)
        ],
        [
            'name' => 'Puan Rohana binti Ismail',
            'role' => 'EXCO Member',
            'tag' => 'Welfare & Community Aid',
            'image' => get_portrait_url(6)
        ],
        [
            'name' => 'Encik Syamsul bin Annuar',
            'role' => 'EXCO Member',
            'tag' => 'Media & PR Campaigns',
            'image' => get_portrait_url(7)
        ],
        [
            'name' => 'Cik Nicole Wong Lee Mei',
            'role' => 'EXCO Member',
            'tag' => 'Youth & Sports',
            'image' => get_portrait_url(8)
        ],
        [
            'name' => 'Encik Zulkifli bin Hashim',
            'role' => 'EXCO Member',
            'tag' => 'Ecosystem Integration',
            'image' => get_portrait_url(9)
        ],
        [
            'name' => 'Puan Hajah Halimah binti Sidek',
            'role' => 'EXCO Member',
            'tag' => 'Women\'s Empowerment',
            'image' => get_portrait_url(10)
        ],
        [
            'name' => 'Dr. Ganesan a/l Murugan',
            'role' => 'EXCO Member',
            'tag' => 'Health & Well-being',
            'image' => get_portrait_url(11)
        ],
        [
            'name' => 'Encik Azhar bin Mansor',
            'role' => 'EXCO Member',
            'tag' => 'Disaster Relief',
            'image' => get_portrait_url(12)
        ],
        [
            'name' => 'Cik Noraini binti Jaffar',
            'role' => 'EXCO Member',
            'tag' => 'Education & Training',
            'image' => get_portrait_url(13)
        ],
        [
            'name' => 'Tuan Haji Sabri bin Yunus',
            'role' => 'EXCO Member',
            'tag' => 'Religious Affairs',
            'image' => get_portrait_url(14)
        ],
        [
            'name' => 'Encik Daniel Tan Boon Hean',
            'role' => 'EXCO Member',
            'tag' => 'Digital & IT Systems',
            'image' => get_portrait_url(15)
        ],
        [
            'name' => 'Puan Norliza binti Kamaruddin',
            'role' => 'EXCO Member',
            'tag' => 'Volunteer Mobilisation',
            'image' => get_portrait_url(16)
        ],
        [
            'name' => 'Encik Farid bin Razali',
            'role' => 'EXCO Member',
            'tag' => 'Economic Mobility',
            'image' => get_portrait_url(17)
        ],
        [
            'name' => 'Cik Sharifah binti Syed Ahmad',
            'role' => 'EXCO Member',
            'tag' => 'Research & Publications',
            'image' => get_portrait_url(18)
        ],
        [
            'name' => 'Dr. Lawrence Lim Kok Seng',
            'role' => 'EXCO Member',
            'tag' => 'Health & Well-being',
            'image' => get_portrait_url(19)
        ],
        [
            'name' => 'Tuan Haji Khairuddin bin Mat',
            'role' => 'EXCO Member',
            'tag' => 'Welfare & Community Aid',
            'image' => get_portrait_url(0)
        ],
        [
            'name' => 'Puan Hajah Fauziah binti Mohd',
            'role' => 'EXCO Member',
            'tag' => 'Women\'s Empowerment',
            'image' => get_portrait_url(1)
        ],
        [
            'name' => 'Encik Rosli bin Rahman',
            'role' => 'EXCO Member',
            'tag' => 'Disaster Relief',
            'image' => get_portrait_url(2)
        ],
        [
            'name' => 'Cik Jasmine Kaur a/p Harjit Singh',
            'role' => 'EXCO Member',
            'tag' => 'Volunteer Mobilisation',
            'image' => get_portrait_url(3)
        ],
        [
            'name' => 'Encik Jeffrey Ong Beng Hui',
            'role' => 'EXCO Member',
            'tag' => 'Youth & Sports',
            'image' => get_portrait_url(4)
        ],
        [
            'name' => 'Ustaz Khairy bin Aznam',
            'role' => 'EXCO Member',
            'tag' => 'Religious Affairs',
            'image' => get_portrait_url(5)
        ],
        [
            'name' => 'Puan Nurashikin binti Jamil',
            'role' => 'EXCO Member',
            'tag' => 'Education & Training',
            'image' => get_portrait_url(6)
        ],
        [
            'name' => 'Encik Muhammad Fikri bin Rosli',
            'role' => 'EXCO Member',
            'tag' => 'Media & PR Campaigns',
            'image' => get_portrait_url(7)
        ],
        [
            'name' => 'Cik Christine Choong Yen Ling',
            'role' => 'EXCO Member',
            'tag' => 'Corporate Partnership',
            'image' => get_portrait_url(8)
        ],
        [
            'name' => 'Tuan Haji Safuan bin Omar',
            'role' => 'EXCO Member',
            'tag' => 'Ecosystem Integration',
            'image' => get_portrait_url(9)
        ],
        [
            'name' => 'Encik Rizal bin Abd Halim',
            'role' => 'EXCO Member',
            'tag' => 'Welfare & Community Aid',
            'image' => get_portrait_url(10)
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Bureau Chairs - 15 members
    |--------------------------------------------------------------------------
    */
    'bureau' => [
        [
            'name' => 'Prof. Madya Dr. Azhar bin Ismail',
            'role' => 'Chairman',
            'tag' => 'Education & Future Readiness',
            'image' => get_portrait_url(11)
        ],
        [
            'name' => 'Encik Ridzuan bin Abu Bakar',
            'role' => 'Chairman',
            'tag' => 'Entrepreneurship & Innovation Development',
            'image' => get_portrait_url(12)
        ],
        [
            'name' => 'Tuan Haji Kamarudin bin Hassan',
            'role' => 'Chairman',
            'tag' => 'Leadership & Capacity Building',
            'image' => get_portrait_url(13)
        ],
        [
            'name' => 'Dato\' Syed Azman bin Syed Ibrahim',
            'role' => 'Chairman',
            'tag' => 'Economic Development',
            'image' => get_portrait_url(14)
        ],
        [
            'name' => 'Datuk Seri Panglima Haji Hashim',
            'role' => 'Chairman',
            'tag' => 'Government Affairs',
            'image' => get_portrait_url(15)
        ],
        [
            'name' => 'Dr. Sharifah Sofiah Al-Attas',
            'role' => 'Chairman',
            'tag' => 'International Liaison',
            'image' => get_portrait_url(16)
        ],
        [
            'name' => 'Ustaz Mohd Asri bin Husin',
            'role' => 'Chairman',
            'tag' => 'Religious Affairs',
            'image' => get_portrait_url(17)
        ],
        [
            'name' => 'Puan Sri Datin Hajah Mastura',
            'role' => 'Chairman',
            'tag' => 'Social Development',
            'image' => get_portrait_url(18)
        ],
        [
            'name' => 'Dr. Siti Mariah binti Mahmud',
            'role' => 'Chairman',
            'tag' => 'Women\'s Development',
            'image' => get_portrait_url(19)
        ],
        [
            'name' => 'Encik Muthusamy a/l Ramasamy',
            'role' => 'Chairman',
            'tag' => 'Community Affairs',
            'image' => get_portrait_url(0)
        ],
        [
            'name' => 'Encik Muhammad Syamil bin Azman',
            'role' => 'Chairman',
            'tag' => 'Youth & Sports',
            'image' => get_portrait_url(1)
        ],
        [
            'name' => 'Puan Zaiton binti Ismail',
            'role' => 'Chairman',
            'tag' => 'Heritage & Culture',
            'image' => get_portrait_url(2)
        ],
        [
            'name' => 'Tuan Haji Zainal Abidin bin Ahmad',
            'role' => 'Chairman',
            'tag' => 'Jammath',
            'image' => get_portrait_url(3)
        ],
        [
            'name' => 'Encik Wan Ahmad Firdaus',
            'role' => 'Chairman',
            'tag' => 'Welfare & Humanitarian Relief',
            'image' => get_portrait_url(4)
        ],
        [
            'name' => 'Puan Rachel Wong',
            'role' => 'Chairman',
            'tag' => 'Digital & Media Relations',
            'image' => get_portrait_url(5)
        ]
    ]
];
