<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FormDropdownOption;
use App\Models\FeedbackSubmission;
use App\Models\OrdinaryMemberSubmission;
use App\Models\FriendMemberSubmission;
use App\Models\MentorSubmission;
use App\Models\PartnerSubmission;
use App\Models\VolunteerSubmission;

class FormDropdownSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Dropdown options data
        $options = [
            'feedback_category' => [
                'Community Development', 'Education & Youth', 'Welfare & Humanitarian',
                'Mosque / NGO Collaboration', 'Digital & Media', 'Volunteerism',
                'Funding / Partnerships', 'Events & Programmes', 'Other'
            ],
            'ordinary_org_type' => [
                'NGO', 'Masjid', 'Surau', 'Madrasah', 'Chambers of Commerce', 'Others'
            ],
            'ordinary_activity' => [
                'Education / Classes', 'Dakwah / Outreach', 'Welfare / Charity',
                'Youth Development', 'Women Programmes', 'Community Services', 'Others'
            ],
            'friends_category' => [
                'Individual', 'Surau', 'Madrasah', 'Others'
            ],
            'mentor_expertise' => [
                'Leadership & Governance', 'Business & Entrepreneurship', 'Education & Youth Development',
                'Career Development', 'Islamic Leadership & Community Building', 'Technology & Digital Innovation',
                'Finance & Investment', 'Media & Communications', 'NGO / Social Impact Management',
                'Legal & Compliance', 'Mental Wellness & Personal Development', 'Other'
            ],
            'mentor_format' => [
                'One-to-One Mentoring', 'Group Mentoring', 'Workshops / Talks',
                'Online Sessions', 'Project-Based Guidance', 'Open to All Formats'
            ],
            'mentor_commitment' => [
                'Monthly', 'Quarterly', 'Event-Based', 'Flexible'
            ],
            'partner_org_type' => [
                'NGO / Foundation', 'Corporate / Private Sector', 'Government Agency',
                'Educational Institution', 'Mosque / Religious Institution', 'Community Association',
                'International Organisation', 'Other'
            ],
            'partner_collaboration' => [
                'Community Development', 'Education & Youth Empowerment', 'Welfare & Humanitarian Assistance',
                'Sponsorship & Funding Support', 'Volunteer Mobilisation', 'Media & Strategic Communications',
                'Technology & Digital Innovation', 'Research & Policy', 'Leadership Development',
                'Events & Programmes', 'Religious / Community Initiatives', 'Other'
            ],
            'partner_type' => [
                'Strategic Partnership', 'Programme Collaboration', 'Sponsorship',
                'CSR Initiative', 'Knowledge / Training Partner', 'Media Partner',
                'Volunteer Support', 'Other'
            ],
            'volunteer_interest' => [
                'Community Outreach', 'Welfare & Humanitarian Aid', 'Education & Youth Development',
                'Event Management', 'Media & Communications', 'Photography / Videography',
                'IT & Digital Support', 'Fundraising', 'Research & Administration',
                'Religious / Islamic Programmes', 'Logistics & Operations', 'Other'
            ],
            'volunteer_mode' => [
                'Physical / On-Ground', 'Virtual / Remote', 'Both'
            ],
            'volunteer_availability' => [
                'Weekdays', 'Weekends', 'Ad Hoc / Event-Based', 'Full Flexible'
            ]
        ];

        foreach ($options as $type => $values) {
            foreach ($values as $index => $value) {
                FormDropdownOption::create([
                    'form_type' => $type,
                    'option_value' => $value,
                    'sort_order' => $index * 10
                ]);
            }
        }

        // 2. Mock Submissions for Feedback
        FeedbackSubmission::create([
            'full_name' => 'Ahmad Fauzi',
            'nric_number' => '850612-14-5543',
            'organisation' => 'Persatuan Belia Prihatin',
            'position' => 'Secretary',
            'state_residency' => 'Selangor',
            'full_address' => '12, Jalan Pandan Indah 4/3, Pandan Indah, 55100 Kuala Lumpur',
            'email' => 'ahmad.fauzi@example.com',
            'contact_number' => '+6012-3456789',
            'categories' => ['Community Development', 'Education & Youth'],
            'suggestion_description' => 'We propose organizing a monthly youth leadership camp in local mosques to encourage civic engagement and leadership skills.',
            'benefits_description' => 'It will help build a strong network of young leaders who can contribute back to the community and support MUKMIN initiatives.',
            'contact_consent' => true,
            'preferred_contact_methods' => ['WhatsApp', 'Email'],
            'declaration_confirmed' => true,
        ]);

        // 3. Mock Submissions for Ordinary Membership
        OrdinaryMemberSubmission::create([
            'name_of_organisation' => 'Pertubuhan Kebajikan Insan Malaysia',
            'org_reg_number' => 'PPM-022-10-22042021',
            'org_reg_date' => '2021-04-22',
            'registered_state' => 'Wilayah Persekutuan Kuala Lumpur',
            'full_address' => 'Block A-3-5, Menara Kelana, Jalan SS 7/19, Kelana Jaya',
            'postcode' => '47301',
            'district_city' => 'Petaling Jaya',
            'year_established' => 2021,
            'total_members_size' => 150,
            'email' => 'contact@kebajikaninsan.org.my',
            'contact_number' => '+603-78789090',
            'website' => 'https://kebajikaninsan.org.my',
            'org_type' => ['Welfare NGO', 'Foundation'],
            'primary_activities' => ['Welfare / Charity', 'Community Services'],
            'is_registered_ros' => true,
            'key_office_bearers' => [
                'president' => ['name' => 'Dato\' Azman Harun', 'email' => 'azman@kebajikaninsan.org.my', 'phone' => '+6019-2223344'],
                'secretary' => ['name' => 'Siti Aminah', 'email' => 'siti@kebajikaninsan.org.my', 'phone' => '+6013-4445566'],
                'treasurer' => ['name' => 'Wong Kar Fai', 'email' => 'wong@kebajikaninsan.org.my', 'phone' => '+6012-7778899']
            ],
            'declaration_confirmed' => true,
            'status' => 'pending'
        ]);

        // 4. Mock Submissions for Friends of MUKMIN
        FriendMemberSubmission::create([
            'entity_type' => 'Individual',
            'ind_name' => 'Mariam Sulaiman',
            'ind_nric' => '920815-10-5412',
            'ind_state' => 'Johor',
            'ind_address' => 'No 45, Jalan Bakri, Muar, 84000 Johor',
            'ind_email' => 'mariam@example.com',
            'ind_phone' => '+6017-8899001',
            'declaration_confirmed' => true,
            'status' => 'approved'
        ]);

        // 5. Mock Submissions for Mentors
        MentorSubmission::create([
            'full_name' => 'Prof. Dr. Ridzuan Ismail',
            'nric_passport' => '700305-08-5119',
            'gender' => 'Male',
            'occupation' => 'Professor of Islamic Finance',
            'organisation' => 'Universiti Malaya',
            'position' => 'Head of Department',
            'experience_years' => 25,
            'state_residency' => 'Wilayah Persekutuan Kuala Lumpur',
            'full_address' => 'A-12-3, Vista Kiara Condominium, Mont Kiara, 50480 Kuala Lumpur',
            'email' => 'ridzuan.ismail@um.edu.my',
            'contact_number' => '+6019-3334455',
            'linkedin' => 'https://linkedin.com/in/dr-ridzuan-ismail',
            'expertise_areas' => ['Finance & Investment', 'Islamic Leadership & Community Building', 'Education & Youth Development'],
            'preferred_format' => ['Workshops / Talks', 'One-to-One Mentoring'],
            'preferred_commitment' => ['Quarterly', 'Flexible'],
            'experience_description' => 'I have 25 years of experience teaching and consulting in Islamic Finance and Economics. I have mentored numerous young scholars and start-ups in the fintech sector.',
            'has_served_before' => true,
            'served_before_details' => 'Served as a board advisor and mentor for the Islamic FinTech hub, Kuala Lumpur.',
            'declaration_confirmed' => true,
        ]);

        // 6. Mock Submissions for Partners
        PartnerSubmission::create([
            'company_name' => 'CelcomDigi Berhad',
            'contact_person' => 'Mohd Hishamuddin',
            'position' => 'Head of CSR Division',
            'org_reg_number' => '199701005118',
            'email' => 'hishamuddin@celcomdigi.com.my',
            'contact_number' => '+6013-3335555',
            'office_address' => 'Laman Celcom, No. 2, Jalan PJS 5/29, 46150 Petaling Jaya',
            'state_country' => 'Selangor',
            'org_type' => ['Corporate / Private Sector'],
            'collaboration_areas' => ['Technology & Digital Innovation', 'Education & Youth Empowerment'],
            'partnership_type' => ['CSR Initiative', 'Sponsorship'],
            'proposal_description' => 'CelcomDigi wants to collaborate with MUKMIN to provide digital literacy and internet packages to B40 madrasah and rural youth, setting up mini digital hubs.',
            'expected_outcomes' => 'Empower at least 500 rural youth with digital capabilities and basic programming knowledge by the end of 2026.',
            'has_collaborated_before' => true,
            'collaborated_before_details' => 'Collaborated with Mercy Malaysia and Yayasan Hijau on various B40 training camps.',
            'supporting_documents' => ['Company / Organisation Profile', 'Proposal Deck'],
            'declaration_confirmed' => true,
            'status' => 'pending'
        ]);

        // 7. Mock Submissions for Volunteers
        VolunteerSubmission::create([
            'full_name' => 'Sarah Nadiah Lim',
            'nric_passport' => '990520-14-6112',
            'gender' => 'Female',
            'occupation_study' => 'Medical Student',
            'organisation' => 'IMU University',
            'state_residency' => 'Selangor',
            'full_address' => 'No. 32, Jalan USJ 11/4G, Subang Jaya, 47620 Selangor',
            'email' => 'sarah.lim@example.com',
            'contact_number' => '+6011-2223344',
            'interest_areas' => ['Welfare & Humanitarian Aid', 'Community Outreach'],
            'skills_expertise' => 'Basic medical training, event organizing, fluent in Malay, English, and Mandarin.',
            'preferred_mode' => 'Both',
            'availability' => ['Weekends', 'Ad Hoc / Event-Based'],
            'has_volunteered_before' => true,
            'volunteered_before_details' => 'Volunteered for flood relief efforts in Kelantan (2024) and vaccination center guide (2021).',
            'emergency_contact_name' => 'Lim Kok Wah',
            'emergency_contact_relationship' => 'Father',
            'emergency_contact_phone' => '+6012-9988776',
            'declaration_confirmed' => true,
        ]);
    }
}
