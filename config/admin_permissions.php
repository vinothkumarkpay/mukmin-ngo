<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin menu items (sidebar)
    |--------------------------------------------------------------------------
    | Each menu item requires its "permission" to be visible in the sidebar.
    */
    'menus' => [
        'panel-overview' => [
            'label' => 'Overview',
            'icon' => 'fa-chart-pie',
            'permission' => 'admin.overview',
        ],
        'panel-feedback' => [
            'label' => 'Feedback & Ideas',
            'icon' => 'fa-comment-alt',
            'permission' => 'submissions.feedback.view',
        ],
        'panel-ordinary' => [
            'label' => 'Ordinary Members',
            'icon' => 'fa-building',
            'permission' => 'submissions.ordinary.view',
        ],
        'panel-friends' => [
            'label' => 'Friends of MUKMIN',
            'icon' => 'fa-users',
            'permission' => 'submissions.friends.view',
        ],
        'panel-mentor' => [
            'label' => 'Mentors',
            'icon' => 'fa-user-tie',
            'permission' => 'submissions.mentor.view',
        ],
        'panel-partner' => [
            'label' => 'Partnerships',
            'icon' => 'fa-handshake',
            'permission' => 'submissions.partner.view',
        ],
        'panel-volunteer' => [
            'label' => 'Volunteers',
            'icon' => 'fa-hands-helping',
            'permission' => 'submissions.volunteer.view',
        ],
        'panel-aid' => [
            'label' => 'Community Aid Requests',
            'icon' => 'fa-hand-holding-medical',
            'permission' => 'submissions.aid.view',
        ],
        'panel-mfls' => [
            'label' => 'MFLS Scholarship Applications',
            'icon' => 'fa-graduation-cap',
            'permission' => 'submissions.mfls.view',
        ],
        'panel-mfls-documents' => [
            'label' => 'MFLS Partner Documents',
            'icon' => 'fa-file-excel',
            'permission' => 'mfls.documents.view',
        ],
        'panel-payments' => [
            'label' => 'Donation Payments',
            'icon' => 'fa-credit-card',
            'permission' => 'donations.view',
            'route' => 'welfare.admin.donation-payments',
        ],
        'panel-contact' => [
            'label' => 'Contact Messages',
            'icon' => 'fa-envelope',
            'permission' => 'submissions.contact.view',
        ],
        'panel-options' => [
            'label' => 'Options Manager',
            'icon' => 'fa-sliders-h',
            'permission' => 'options.manage',
            'divider' => true,
        ],
        'panel-users' => [
            'label' => 'User Management',
            'icon' => 'fa-users-cog',
            'permission' => 'admin.users.manage',
            'route' => 'welfare.admin.users.index',
        ],
        'panel-roles' => [
            'label' => 'Roles & Permissions',
            'icon' => 'fa-shield-alt',
            'permission' => 'admin.roles.manage',
            'route' => 'welfare.admin.roles.index',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission definitions
    |--------------------------------------------------------------------------
    */
    'permissions' => [
        'admin.overview' => [
            'label' => 'View dashboard overview',
            'group' => 'Dashboard',
            'description' => 'Access the main overview panel with submission counts.',
        ],
        'admin.users.manage' => [
            'label' => 'Manage admin users',
            'group' => 'Administration',
            'description' => 'Create, edit, and deactivate admin user accounts.',
        ],
        'admin.roles.manage' => [
            'label' => 'Manage roles & permissions',
            'group' => 'Administration',
            'description' => 'Create roles and configure menu access and permissions.',
        ],

        'submissions.feedback.view' => ['label' => 'View feedback submissions', 'group' => 'Feedback & Ideas'],
        'submissions.feedback.export' => ['label' => 'Export feedback submissions', 'group' => 'Feedback & Ideas'],
        'submissions.feedback.import' => ['label' => 'Import feedback submissions', 'group' => 'Feedback & Ideas'],
        'submissions.feedback.status' => ['label' => 'Update feedback status', 'group' => 'Feedback & Ideas'],

        'submissions.ordinary.view' => ['label' => 'View ordinary member submissions', 'group' => 'Ordinary Members'],
        'submissions.ordinary.export' => ['label' => 'Export ordinary member submissions', 'group' => 'Ordinary Members'],
        'submissions.ordinary.import' => ['label' => 'Import ordinary member submissions', 'group' => 'Ordinary Members'],
        'submissions.ordinary.status' => ['label' => 'Update ordinary member status', 'group' => 'Ordinary Members'],

        'submissions.friends.view' => ['label' => 'View friends submissions', 'group' => 'Friends of MUKMIN'],
        'submissions.friends.export' => ['label' => 'Export friends submissions', 'group' => 'Friends of MUKMIN'],
        'submissions.friends.import' => ['label' => 'Import friends submissions', 'group' => 'Friends of MUKMIN'],
        'submissions.friends.status' => ['label' => 'Update friends status', 'group' => 'Friends of MUKMIN'],

        'submissions.mentor.view' => ['label' => 'View mentor submissions', 'group' => 'Mentors'],
        'submissions.mentor.export' => ['label' => 'Export mentor submissions', 'group' => 'Mentors'],
        'submissions.mentor.import' => ['label' => 'Import mentor submissions', 'group' => 'Mentors'],
        'submissions.mentor.status' => ['label' => 'Update mentor status', 'group' => 'Mentors'],

        'submissions.partner.view' => ['label' => 'View partnership submissions', 'group' => 'Partnerships'],
        'submissions.partner.export' => ['label' => 'Export partnership submissions', 'group' => 'Partnerships'],
        'submissions.partner.import' => ['label' => 'Import partnership submissions', 'group' => 'Partnerships'],
        'submissions.partner.status' => ['label' => 'Update partnership status', 'group' => 'Partnerships'],

        'submissions.volunteer.view' => ['label' => 'View volunteer submissions', 'group' => 'Volunteers'],
        'submissions.volunteer.export' => ['label' => 'Export volunteer submissions', 'group' => 'Volunteers'],
        'submissions.volunteer.import' => ['label' => 'Import volunteer submissions', 'group' => 'Volunteers'],
        'submissions.volunteer.status' => ['label' => 'Update volunteer status', 'group' => 'Volunteers'],

        'submissions.aid.view' => ['label' => 'View community aid submissions', 'group' => 'Community Aid'],
        'submissions.aid.export' => ['label' => 'Export community aid submissions', 'group' => 'Community Aid'],
        'submissions.aid.import' => ['label' => 'Import community aid submissions', 'group' => 'Community Aid'],
        'submissions.aid.status' => ['label' => 'Update community aid status', 'group' => 'Community Aid'],

        'submissions.mfls.view' => ['label' => 'View MFLS scholarship submissions', 'group' => 'MFLS Scholarships'],
        'submissions.mfls.export' => ['label' => 'Export MFLS scholarship submissions', 'group' => 'MFLS Scholarships'],
        'submissions.mfls.import' => ['label' => 'Import MFLS scholarship submissions', 'group' => 'MFLS Scholarships'],
        'submissions.mfls.status' => ['label' => 'Update MFLS scholarship status', 'group' => 'MFLS Scholarships'],

        'submissions.contact.view' => ['label' => 'View contact messages', 'group' => 'Contact Messages'],
        'submissions.contact.export' => ['label' => 'Export contact messages', 'group' => 'Contact Messages'],
        'submissions.contact.import' => ['label' => 'Import contact messages', 'group' => 'Contact Messages'],
        'submissions.contact.status' => ['label' => 'Update contact message status', 'group' => 'Contact Messages'],

        'donations.view' => [
            'label' => 'View donation payments',
            'group' => 'Donations',
            'description' => 'Access the donation payments page and records.',
        ],

        'mfls.documents.view' => ['label' => 'View MFLS partner documents', 'group' => 'MFLS Documents'],
        'mfls.documents.upload' => ['label' => 'Upload MFLS partner documents', 'group' => 'MFLS Documents'],

        'options.manage' => [
            'label' => 'Manage form dropdown options',
            'group' => 'Options Manager',
            'description' => 'Add, edit, and delete form dropdown options.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Submission type to permission prefix mapping
    |--------------------------------------------------------------------------
    */
    'submission_types' => [
        'feedback' => 'submissions.feedback',
        'ordinary' => 'submissions.ordinary',
        'friends' => 'submissions.friends',
        'mentor' => 'submissions.mentor',
        'partner' => 'submissions.partner',
        'volunteer' => 'submissions.volunteer',
        'contact' => 'submissions.contact',
        'aid' => 'submissions.aid',
        'mfls' => 'submissions.mfls',
    ],
];
