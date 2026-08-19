<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Welfare\Concerns\ChecksAdminAccess;
use Illuminate\Http\Request;
use App\Models\FormDropdownOption;
use App\Models\FeedbackSubmission;
use App\Models\OrdinaryMemberSubmission;
use App\Models\FriendMemberSubmission;
use App\Models\MentorSubmission;
use App\Models\PartnerSubmission;
use App\Models\VolunteerSubmission;
use App\Models\ContactSubmission;
use App\Models\CommunityAidSubmission;
use App\Models\Donation;
use App\Models\MflsScholarshipSubmission;
use App\Services\Welfare\MflsPartnerDocumentService;
use App\Services\Welfare\SubmissionImportRegistry;
use App\Services\Welfare\SubmissionImporter;
use App\Services\Welfare\SubmissionStatusNotifier;
use App\Support\SubmissionStatus;
use Response;

class AdminDashboardController extends Controller
{
    use ChecksAdminAccess;

    /** @var array<string, string> */
    private const SUBMISSION_TAB_TYPES = [
        'panel-feedback' => 'feedback',
        'panel-ordinary' => 'ordinary',
        'panel-friends' => 'friends',
        'panel-mentor' => 'mentor',
        'panel-partner' => 'partner',
        'panel-volunteer' => 'volunteer',
        'panel-contact' => 'contact',
        'panel-aid' => 'aid',
        'panel-mfls' => 'mfls',
    ];

    public function index(Request $request)
    {
        $activeTab = (string) $request->get('admin_tab', 'panel-overview');

        if (! $this->access()->userCanAccessTab($this->adminUser(), $activeTab)) {
            $activeTab = $this->access()->defaultFirstAccessibleTab($this->adminUser());
        }

        $allowedPanelIds = collect($this->access()->menuItemsForUser($this->adminUser()))
            ->pluck('id')
            ->all();

        // 1. Gather stats (+ per-status breakdowns for Overview cards)
        $stats = [
            'feedback' => FeedbackSubmission::count(),
            'ordinary' => OrdinaryMemberSubmission::count(),
            'friends' => FriendMemberSubmission::count(),
            'mentor' => MentorSubmission::count(),
            'partner' => PartnerSubmission::count(),
            'volunteer' => VolunteerSubmission::count(),
            'contact' => ContactSubmission::count(),
            'aid' => CommunityAidSubmission::count(),
            'mfls' => MflsScholarshipSubmission::count(),
            'donations' => Donation::count(),
        ];

        $statBreakdowns = [
            'feedback' => $this->submissionStatusBreakdown(FeedbackSubmission::class),
            'ordinary' => $this->submissionStatusBreakdown(OrdinaryMemberSubmission::class),
            'friends' => $this->submissionStatusBreakdown(FriendMemberSubmission::class),
            'mentor' => $this->submissionStatusBreakdown(MentorSubmission::class),
            'partner' => $this->submissionStatusBreakdown(PartnerSubmission::class),
            'volunteer' => $this->submissionStatusBreakdown(VolunteerSubmission::class),
            'contact' => $this->submissionStatusBreakdown(ContactSubmission::class),
            'aid' => $this->submissionStatusBreakdown(CommunityAidSubmission::class),
            'mfls' => $this->submissionStatusBreakdown(MflsScholarshipSubmission::class),
            'donations' => $this->donationStatusBreakdown(),
        ];

        $submissionFilters = $this->resolveSubmissionFilters($request);
        $hasSubmissionFilters = $this->hasActiveSubmissionFilters($submissionFilters);

        $filteredTabType = ($hasSubmissionFilters && isset(self::SUBMISSION_TAB_TYPES[$activeTab]))
            ? self::SUBMISSION_TAB_TYPES[$activeTab]
            : null;

        // 2. Fetch submissions (filters apply to the active tab only)
        $feedback = $this->loadSubmissions('feedback', FeedbackSubmission::query(), $submissionFilters, $filteredTabType);
        $ordinary = $this->loadSubmissions('ordinary', OrdinaryMemberSubmission::query(), $submissionFilters, $filteredTabType);
        $friends = $this->loadSubmissions('friends', FriendMemberSubmission::query(), $submissionFilters, $filteredTabType);
        $mentor = $this->loadSubmissions('mentor', MentorSubmission::query(), $submissionFilters, $filteredTabType);
        $partner = $this->loadSubmissions('partner', PartnerSubmission::query(), $submissionFilters, $filteredTabType);
        $volunteer = $this->loadSubmissions('volunteer', VolunteerSubmission::query(), $submissionFilters, $filteredTabType);
        $contact = $this->loadSubmissions('contact', ContactSubmission::query(), $submissionFilters, $filteredTabType);
        $aid = $this->loadSubmissions('aid', CommunityAidSubmission::query(), $submissionFilters, $filteredTabType);
        $mfls = $this->loadSubmissions('mfls', MflsScholarshipSubmission::query(), $submissionFilters, $filteredTabType);

        $currentTabType = self::SUBMISSION_TAB_TYPES[$activeTab] ?? null;
        $filteredSubmissionCount = match ($currentTabType) {
            'feedback' => $feedback->count(),
            'ordinary' => $ordinary->count(),
            'friends' => $friends->count(),
            'mentor' => $mentor->count(),
            'partner' => $partner->count(),
            'volunteer' => $volunteer->count(),
            'contact' => $contact->count(),
            'aid' => $aid->count(),
            'mfls' => $mfls->count(),
            default => $feedback->count()
                + $ordinary->count()
                + $friends->count()
                + $mentor->count()
                + $partner->count()
                + $volunteer->count()
                + $contact->count()
                + $aid->count()
                + $mfls->count(),
        };

        $donationPayments = Donation::query()->orderBy('created_at', 'desc')->get();
        $donationPaymentMethods = Donation::query()
            ->whereNotNull('payment_method')
            ->distinct()
            ->orderBy('payment_method')
            ->pluck('payment_method');

        app(MflsPartnerDocumentService::class)->bootstrapDocumentsIfMissing();
        $mflsPartnerDocuments = app(MflsPartnerDocumentService::class)->documentsForAdmin();
        $options = FormDropdownOption::orderBy('form_type')
            ->orderBy('sort_order')
            ->orderBy('option_value')
            ->get()
            ->groupBy('form_type');

        // Form labels map
        $formTypesMap = [
            'feedback_category' => 'Feedback Categories',
            'ordinary_org_type' => 'Ordinary Member Organisation Types',
            'ordinary_activity' => 'Ordinary Member Primary Activities',
            'friends_category' => 'Friends of MUKMIN Entity Types',
            'mentor_expertise' => 'Mentor Areas of Expertise',
            'mentor_format' => 'Mentor Preferred Formats',
            'mentor_commitment' => 'Mentor Commitments',
            'partner_org_type' => 'Partner Organisation Types',
            'partner_collaboration' => 'Partner Collaboration Areas',
            'partner_type' => 'Partner Partnership Types',
            'volunteer_interest' => 'Volunteer Areas of Interest',
            'volunteer_mode' => 'Volunteer Preferred Modes',
            'volunteer_availability' => 'Volunteer Availabilities',
        ];

        $submissionFilterStates = $this->malaysianStateOptions();
        $submissionFilterPartners = collect(config('mfls_partners.institutions', []))
            ->map(fn ($partner) => [
                'id' => $partner['id'],
                'name' => $partner['name'],
            ])
            ->values()
            ->all();
        $submissionFilterQualifications = ['SPM', 'STPM', 'IGCSE', 'Foundation', 'Diploma', 'Degree'];
        $submissionFilterHouseholdIncomes = ['Below RM 2,000', 'RM 2,001 to RM 5,000'];
        $submissionFilterEntityTypes = [
            'Individual',
            'Non-registered NGO',
            'Non-registered Surau',
            'Non-registered Madrasah',
            'Others',
        ];
        $submissionStatusFilter = $submissionFilters['status'] ?? null;

        return view('welfare.admin.dashboard', compact(
            'stats',
            'statBreakdowns',
            'feedback',
            'ordinary',
            'friends',
            'mentor',
            'partner',
            'volunteer',
            'contact',
            'aid',
            'mfls',
            'donationPayments',
            'donationPaymentMethods',
            'options',
            'formTypesMap',
            'mflsPartnerDocuments',
            'submissionStatusFilter',
            'submissionFilters',
            'filteredSubmissionCount',
            'filteredTabType',
            'activeTab',
            'allowedPanelIds',
            'submissionFilterStates',
            'submissionFilterPartners',
            'submissionFilterQualifications',
            'submissionFilterHouseholdIncomes',
            'submissionFilterEntityTypes',
        ))->with('submissionStatusOptions', SubmissionStatus::options());
    }

    /**
     * @param  class-string  $modelClass
     * @return array<string, int>
     */
    private function submissionStatusBreakdown(string $modelClass): array
    {
        $counts = array_fill_keys(SubmissionStatus::values(), 0);

        $raw = $modelClass::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        foreach ($raw as $status => $count) {
            $normalized = SubmissionStatus::normalize($status !== null && $status !== '' ? (string) $status : null);
            if (! array_key_exists($normalized, $counts)) {
                $counts[$normalized] = 0;
            }
            $counts[$normalized] += (int) $count;
        }

        return $counts;
    }

    /**
     * @return array<string, int>
     */
    private function donationStatusBreakdown(): array
    {
        $counts = [
            'pending' => 0,
            'paid' => 0,
            'failed' => 0,
        ];

        $raw = Donation::query()
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        foreach ($raw as $status => $count) {
            $key = strtolower(trim((string) ($status ?? 'pending')));
            if ($key === '') {
                $key = 'pending';
            }
            if (! array_key_exists($key, $counts)) {
                $counts[$key] = 0;
            }
            $counts[$key] += (int) $count;
        }

        return $counts;
    }

    /**
     * @return array<string, mixed>
     */
    private function resolveSubmissionFilters(Request $request): array
    {
        $status = $request->filled('submission_status')
            ? SubmissionStatus::normalize($request->input('submission_status'))
            : null;

        return [
            'status' => $status,
            'q' => trim((string) $request->input('filter_q', '')),
            'state' => trim((string) $request->input('filter_state', '')),
            'date_from' => trim((string) $request->input('filter_date_from', '')),
            'date_to' => trim((string) $request->input('filter_date_to', '')),
            'partner' => trim((string) $request->input('filter_partner', '')),
            'programme' => trim((string) $request->input('filter_programme', '')),
            'qualification' => trim((string) $request->input('filter_qualification', '')),
            'household_income' => trim((string) $request->input('filter_household_income', '')),
            'gender' => trim((string) $request->input('filter_gender', '')),
            'mode' => trim((string) $request->input('filter_mode', '')),
            'entity_type' => trim((string) $request->input('filter_entity_type', '')),
            'ros' => $request->input('filter_ros'),
            'aid_type' => trim((string) $request->input('filter_aid_type', '')),
        ];
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function hasActiveSubmissionFilters(array $filters): bool
    {
        foreach ($filters as $key => $value) {
            if ($key === 'ros') {
                if ($value === '0' || $value === '1' || $value === 0 || $value === 1) {
                    return true;
                }
                continue;
            }

            if (filled($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function loadSubmissions(string $type, $query, array $filters, ?string $filteredTabType)
    {
        $applyFilter = $filteredTabType === $type;
        $results = $this->filteredSubmissionsQuery($query, $applyFilter ? $filters : [], $type)->get();

        if (! $applyFilter || empty($filters['status'])) {
            return $results;
        }

        return $results
            ->filter(fn ($item) => SubmissionStatus::matchesFilter($item->status, $filters['status']))
            ->values();
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function filteredSubmissionsQuery($query, array $filters, string $type)
    {
        $query->orderBy('created_at', 'desc');

        if ($filters === []) {
            return $query;
        }

        if (! empty($filters['status'])) {
            $query->whereIn('status', SubmissionStatus::storedValuesFor($filters['status']));
        }

        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $search = $filters['q'] ?? '';
        if ($search !== '') {
            $like = '%' . $search . '%';
            $query->where(function ($builder) use ($type, $like) {
                foreach ($this->searchColumnsForType($type) as $column) {
                    $builder->orWhere($column, 'like', $like);
                }
            });
        }

        $state = $filters['state'] ?? '';
        if ($state !== '') {
            $stateColumns = $this->stateColumnsForType($type);
            if ($stateColumns !== []) {
                $query->where(function ($builder) use ($stateColumns, $state) {
                    foreach ($stateColumns as $column) {
                        $builder->orWhere($column, $state);
                    }
                });
            }
        }

        if ($type === 'mfls') {
            if (! empty($filters['partner'])) {
                $query->where('partner_institution_id', $filters['partner']);
            }
            if (! empty($filters['programme'])) {
                $query->where('programme_course_applied', 'like', '%' . $filters['programme'] . '%');
            }
            if (! empty($filters['qualification'])) {
                $query->where('current_qualification', $filters['qualification']);
            }
            if (! empty($filters['household_income'])) {
                $query->where('household_income', $filters['household_income']);
            }
        }

        if ($type === 'volunteer') {
            if (! empty($filters['gender'])) {
                $query->where('gender', $filters['gender']);
            }
            if (! empty($filters['mode'])) {
                $query->where('preferred_mode', 'like', '%' . $filters['mode'] . '%');
            }
        }

        if ($type === 'friends' && ! empty($filters['entity_type'])) {
            $query->where('entity_type', $filters['entity_type']);
        }

        if ($type === 'ordinary' && ($filters['ros'] === '0' || $filters['ros'] === '1')) {
            $query->where('is_registered_ros', (bool) ((int) $filters['ros']));
        }

        if ($type === 'aid' && ! empty($filters['aid_type'])) {
            $query->where('type_of_aid', 'like', '%' . $filters['aid_type'] . '%');
        }

        return $query;
    }

    /** @return list<string> */
    private function searchColumnsForType(string $type): array
    {
        return match ($type) {
            'feedback' => ['full_name', 'email', 'organisation'],
            'ordinary' => ['name_of_organisation', 'email'],
            'friends' => ['ind_name', 'org_name', 'ind_email', 'org_email', 'org_contact_person_name'],
            'mentor' => ['full_name', 'email', 'organisation', 'occupation'],
            'partner' => ['company_name', 'contact_person', 'email'],
            'volunteer' => ['full_name', 'email', 'contact_number'],
            'contact' => ['name', 'email', 'phone'],
            'aid' => ['full_name', 'email', 'contact_number'],
            'mfls' => ['full_name', 'email', 'programme_course_applied', 'partner_institution_name'],
            default => [],
        };
    }

    /** @return list<string> */
    private function stateColumnsForType(string $type): array
    {
        return match ($type) {
            'feedback' => ['state_residency'],
            'ordinary' => ['state', 'registered_state'],
            'friends' => ['ind_state', 'org_state'],
            'mentor' => ['state_residency'],
            'partner' => ['state_country'],
            'volunteer' => ['state_residency'],
            'aid' => ['state_residency'],
            'mfls' => ['state'],
            default => [],
        };
    }

    /** @return list<string> */
    private function malaysianStateOptions(): array
    {
        return [
            'Johor',
            'Kedah',
            'Kelantan',
            'Melaka',
            'Negeri Sembilan',
            'Pahang',
            'Perak',
            'Perlis',
            'Pulau Pinang',
            'Sabah',
            'Sarawak',
            'Selangor',
            'Terengganu',
            'Wilayah Persekutuan Kuala Lumpur',
            'Wilayah Persekutuan Labuan',
            'Wilayah Persekutuan Putrajaya',
        ];
    }

    public function donationPayments(Request $request)
    {
        $this->authorizePermission('donations.view');

        $donationPayments = $this->filteredDonationPaymentsQuery($request)->get();
        $donationPaymentMethods = Donation::query()
            ->whereNotNull('payment_method')
            ->distinct()
            ->orderBy('payment_method')
            ->pluck('payment_method');

        return view('welfare.admin.donation-payments', compact(
            'donationPayments',
            'donationPaymentMethods'
        ));
    }

    private function filteredDonationPaymentsQuery(Request $request)
    {
        $query = Donation::query()->orderBy('created_at', 'desc');

        if ($request->filled('payment_status')) {
            $query->where('status', $request->payment_status);
        }

        $donorName = trim((string) $request->input('donor_name', $request->input('name', '')));
        if ($donorName !== '') {
            $query->where('name', 'like', '%' . addcslashes($donorName, '%_\\') . '%');
        }

        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . addcslashes(trim($request->email), '%_\\') . '%');
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', 'like', '%' . addcslashes(trim($request->order_id), '%_\\') . '%');
        }

        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        return $query;
    }

    public function showSubmission($type, $id)
    {
        if ($type === 'donation') {
            $this->authorizePermission('donations.view');
        } else {
            $this->authorizeSubmission($type, 'view');
        }

        $submission = null;
        switch ($type) {
            case 'feedback':
                $submission = FeedbackSubmission::find($id);
                break;
            case 'ordinary':
                $submission = OrdinaryMemberSubmission::find($id);
                break;
            case 'friends':
                $submission = FriendMemberSubmission::find($id);
                break;
            case 'mentor':
                $submission = MentorSubmission::find($id);
                break;
            case 'partner':
                $submission = PartnerSubmission::find($id);
                break;
            case 'volunteer':
                $submission = VolunteerSubmission::find($id);
                break;
            case 'contact':
                $submission = ContactSubmission::find($id);
                break;
            case 'aid':
                $submission = CommunityAidSubmission::find($id);
                break;
            case 'mfls':
                $submission = MflsScholarshipSubmission::find($id);
                break;
            case 'donation':
                $submission = Donation::find($id);
                break;
        }

        if (!$submission) {
            return response()->json(['error' => 'Submission not found.'], 404);
        }

        return response()->json($submission);
    }

    public function updateStatus(Request $request, $type, $id)
    {
        $this->authorizeSubmission($type, 'status');

        $validated = $request->validate([
            'status' => SubmissionStatus::validationRule(),
        ]);

        $submission = $this->findSubmissionForStatusUpdate($type, $id);

        if (!$submission) {
            return response()->json(['error' => 'Invalid submission type for status update.'], 400);
        }

        $submission->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'status' => $validated['status'],
            'label' => SubmissionStatus::label($validated['status']),
        ]);
    }

    public function notifyStatusUpdate($type, $id, SubmissionStatusNotifier $notifier)
    {
        $this->authorizeSubmission($type, 'status');

        $submission = $this->findSubmissionForStatusUpdate($type, $id);

        if (! $submission) {
            return response()->json(['error' => 'Invalid submission type for status notification.'], 400);
        }

        if (! $notifier->resolveApplicantEmail($submission)) {
            return response()->json(['error' => 'No applicant email address is available for this submission.'], 422);
        }

        try {
            $notifier->notifyApplicant($submission, $type);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to send status notification email.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status notification email sent to the applicant.',
        ]);
    }

    private function findSubmissionForStatusUpdate(string $type, $id)
    {
        switch ($type) {
            case 'feedback':
                return FeedbackSubmission::findOrFail($id);
            case 'ordinary':
                return OrdinaryMemberSubmission::findOrFail($id);
            case 'friends':
                return FriendMemberSubmission::findOrFail($id);
            case 'mentor':
                return MentorSubmission::findOrFail($id);
            case 'partner':
                return PartnerSubmission::findOrFail($id);
            case 'volunteer':
                return VolunteerSubmission::findOrFail($id);
            case 'contact':
                return ContactSubmission::findOrFail($id);
            case 'aid':
                return CommunityAidSubmission::findOrFail($id);
            case 'mfls':
                return MflsScholarshipSubmission::findOrFail($id);
            default:
                return null;
        }
    }

    // Dynamic dropdown option management
    public function addOption(Request $request)
    {
        $this->authorizePermission('options.manage');

        $validated = $request->validate([
            'form_type' => 'required|string|max:100',
            'option_value' => 'required|string|max:255',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        FormDropdownOption::create($validated);

        return redirect()->back()->with('success', 'Dropdown option added successfully!');
    }

    public function editOption(Request $request, $id)
    {
        $this->authorizePermission('options.manage');

        $validated = $request->validate([
            'option_value' => 'required|string|max:255',
            'sort_order' => 'required|integer',
        ]);

        $option = FormDropdownOption::findOrFail($id);
        $option->update($validated);

        return redirect()->back()->with('success', 'Dropdown option updated successfully!');
    }

    public function deleteOption($id)
    {
        $this->authorizePermission('options.manage');

        $option = FormDropdownOption::findOrFail($id);
        $option->delete();

        return redirect()->back()->with('success', 'Dropdown option deleted successfully!');
    }

    // Export submissions to CSV format
    public function exportCsv($type)
    {
        $this->authorizeSubmission($type, 'export');

        $filename = "submissions_{$type}_" . date('Ymd_His') . ".csv";
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($type) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            switch ($type) {
                case 'feedback':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC', 'Organisation', 'Position', 'State', 'Address', 'Email', 'Phone', 'Categories', 'Other Category', 'Suggestion', 'Benefits', 'Contact Consent', 'Preferred Methods', 'Status']);
                    foreach (FeedbackSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_number,
                            $item->organisation,
                            $item->position,
                            $item->state_residency,
                            $item->full_address,
                            $item->email,
                            $item->contact_number,
                            is_array($item->categories) ? implode(', ', $item->categories) : $item->categories,
                            $item->other_category,
                            $item->suggestion_description,
                            $item->benefits_description,
                            $item->contact_consent ? 'Yes' : 'No',
                            is_array($item->preferred_contact_methods) ? implode(', ', $item->preferred_contact_methods) : $item->preferred_contact_methods,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'ordinary':
                    fputcsv($file, ['ID', 'Date', 'Organisation Name', 'Reg Number', 'Reg Date', 'State', 'Address', 'Postcode', 'City', 'Established', 'Congregation Size', 'Email', 'Phone', 'Website', 'Org Types', 'Org Types Other', 'Activities', 'Activities Other', 'Registered ROS', 'President Name', 'President Email', 'President Phone', 'Secretary Name', 'Secretary Email', 'Secretary Phone', 'Status']);
                    foreach (OrdinaryMemberSubmission::all() as $item) {
                        $bearers = $item->key_office_bearers;
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->name_of_organisation,
                            $item->org_reg_number,
                            $item->org_reg_date ? $item->org_reg_date->format('Y-m-d') : '',
                            $item->registered_state,
                            $item->full_address,
                            $item->postcode,
                            $item->district_city,
                            $item->year_established,
                            $item->total_members_size,
                            $item->email,
                            $item->contact_number,
                            $item->website,
                            is_array($item->org_type) ? implode(', ', $item->org_type) : $item->org_type,
                            $item->org_type_other,
                            is_array($item->primary_activities) ? implode(', ', $item->primary_activities) : $item->primary_activities,
                            $item->primary_activities_other,
                            $item->is_registered_ros ? 'Yes' : 'No',
                            $bearers['president']['name'] ?? '',
                            $bearers['president']['email'] ?? '',
                            $bearers['president']['phone'] ?? '',
                            $bearers['secretary']['name'] ?? '',
                            $bearers['secretary']['email'] ?? '',
                            $bearers['secretary']['phone'] ?? '',
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'friends':
                    fputcsv($file, ['ID', 'Date', 'Type', 'Others Specify', 'Org Name', 'Org State', 'Org Address', 'Org Email', 'Org Phone', 'Official Contact Person Name', 'Org Website', 'Ind Name', 'Ind NRIC', 'Ind State', 'Ind Profession', 'Ind Profession Other', 'Ind Address', 'Ind Email', 'Ind Phone', 'Ind Area of Interest', 'Status']);
                    foreach (FriendMemberSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->entity_type,
                            $item->others_specify,
                            $item->org_name,
                            $item->org_state,
                            $item->org_address,
                            $item->org_email,
                            $item->org_phone,
                            $item->org_contact_person_name,
                            $item->org_website,
                            $item->ind_name,
                            $item->ind_nric,
                            $item->ind_state,
                            $item->ind_profession,
                            $item->ind_profession_other,
                            $item->ind_address,
                            $item->ind_email,
                            $item->ind_phone,
                            $item->ind_area_of_interest,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'mentor':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC/Passport', 'Gender', 'Occupation', 'Organisation', 'Position', 'Years Experience', 'State', 'Address', 'Email', 'Phone', 'LinkedIn', 'Expertise Areas', 'Expertise Other', 'Formats', 'Commitments', 'Experience Description', 'Has Served Before', 'Served Before Details', 'Status']);
                    foreach (MentorSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_passport,
                            $item->gender,
                            $item->occupation,
                            $item->organisation,
                            $item->position,
                            $item->experience_years,
                            $item->state_residency,
                            $item->full_address,
                            $item->email,
                            $item->contact_number,
                            $item->linkedin,
                            is_array($item->expertise_areas) ? implode(', ', $item->expertise_areas) : $item->expertise_areas,
                            $item->expertise_other,
                            is_array($item->preferred_format) ? implode(', ', $item->preferred_format) : $item->preferred_format,
                            is_array($item->preferred_commitment) ? implode(', ', $item->preferred_commitment) : $item->preferred_commitment,
                            $item->experience_description,
                            $item->has_served_before ? 'Yes' : 'No',
                            $item->served_before_details,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'partner':
                    fputcsv($file, ['ID', 'Date', 'Company Name', 'Contact Person', 'Position', 'Reg Number', 'Email', 'Phone', 'Office Address', 'State/Country', 'Org Types', 'Org Types Other', 'Collaboration Areas', 'Collaboration Other', 'Partnership Types', 'Partnership Other', 'Proposal Details', 'Expected Outcomes', 'Has Collaborated', 'Previous Collab Details', 'Supporting Documents', 'Status']);
                    foreach (PartnerSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->company_name,
                            $item->contact_person,
                            $item->position,
                            $item->org_reg_number,
                            $item->email,
                            $item->contact_number,
                            $item->office_address,
                            $item->state_country,
                            is_array($item->org_type) ? implode(', ', $item->org_type) : $item->org_type,
                            $item->org_type_other,
                            is_array($item->collaboration_areas) ? implode(', ', $item->collaboration_areas) : $item->collaboration_areas,
                            $item->collaboration_other,
                            is_array($item->partnership_type) ? implode(', ', $item->partnership_type) : $item->partnership_type,
                            $item->partnership_other,
                            $item->proposal_description,
                            $item->expected_outcomes,
                            $item->has_collaborated_before ? 'Yes' : 'No',
                            $item->collaborated_before_details,
                            is_array($item->supporting_documents) ? implode(', ', $item->supporting_documents) : $item->supporting_documents,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'volunteer':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC/Passport', 'Gender', 'Occupation/Study', 'Organisation', 'State', 'Address', 'Email', 'Phone', 'Interest Areas', 'Interest Other', 'Skills/Expertise', 'Preferred Mode', 'Availability', 'Has Volunteered Before', 'Volunteered Details', 'Emergency Name', 'Emergency Relationship', 'Emergency Phone', 'Status']);
                    foreach (VolunteerSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_passport,
                            $item->gender,
                            $item->occupation_study,
                            $item->organisation,
                            $item->state_residency,
                            $item->full_address,
                            $item->email,
                            $item->contact_number,
                            is_array($item->interest_areas) ? implode(', ', $item->interest_areas) : $item->interest_areas,
                            $item->interest_other,
                            $item->skills_expertise,
                            $item->preferred_mode,
                            is_array($item->availability) ? implode(', ', $item->availability) : $item->availability,
                            $item->has_volunteered_before ? 'Yes' : 'No',
                            $item->volunteered_before_details,
                            $item->emergency_contact_name,
                            $item->emergency_contact_relationship,
                            $item->emergency_contact_phone,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'contact':
                    fputcsv($file, ['ID', 'Date', 'Name', 'Email', 'Phone', 'Message', 'Status']);
                    foreach (ContactSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->name,
                            $item->email,
                            $item->phone,
                            $item->message,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'aid':
                    fputcsv($file, ['ID', 'Date', 'Full Name', 'NRIC/Passport', 'Gender', 'DOB', 'Nationality', 'Occupation', 'Monthly Income', 'Phone', 'Email', 'Address', 'State', 'Type of Aid', 'Type of Aid Other', 'Situation', 'Who Benefits', 'Beneficiaries Count', 'Received Aid Before', 'Previous Aid Details', 'Status']);
                    foreach (CommunityAidSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->full_name,
                            $item->nric_passport,
                            $item->gender,
                            $item->dob ? $item->dob->format('Y-m-d') : '',
                            $item->nationality,
                            $item->occupation,
                            $item->monthly_income,
                            $item->contact_number,
                            $item->email,
                            $item->full_address,
                            $item->state_residency,
                            is_array($item->type_of_aid) ? implode(', ', $item->type_of_aid) : $item->type_of_aid,
                            $item->type_of_aid_other,
                            $item->situation_description,
                            $item->who_benefits,
                            $item->number_of_beneficiaries,
                            $item->received_aid_before ? 'Yes' : 'No',
                            $item->received_aid_before_details,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;

                case 'mfls':
                    fputcsv($file, ['ID', 'Date', 'Email', 'Full Name', 'NRIC', 'DOB', 'Gender', 'Age', 'Citizenship', 'Marital Status', 'Phone', 'Address', 'State', 'Postcode', 'Partner Institution', 'Selected Programme', 'Current Qualification', 'Institution', 'Year of Completion', 'CGPA/Result', 'Household Income', 'Father Name', 'Father Occupation', 'Mother Name', 'Mother Occupation', 'Dependents', 'Sibling Information', 'Other Scholarship', 'Status']);
                    foreach (MflsScholarshipSubmission::all() as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->created_at,
                            $item->email,
                            $item->full_name,
                            $item->nric_passport,
                            $item->dob ? $item->dob->format('Y-m-d') : '',
                            $item->gender,
                            $item->age,
                            $item->citizenship,
                            $item->marital_status,
                            $item->contact_number,
                            $item->full_address,
                            $item->state,
                            $item->postcode,
                            $item->partner_institution_name,
                            $item->programme_course_applied,
                            $item->current_qualification,
                            $item->institution_name,
                            $item->year_of_completion,
                            $item->current_cgpa_result,
                            $item->household_income,
                            $item->father_guardian_name,
                            $item->father_guardian_occupation,
                            $item->mother_guardian_name,
                            $item->mother_guardian_occupation,
                            $item->number_of_dependents,
                            $item->sibling_information ? json_encode($item->sibling_information) : '',
                            $item->other_scholarship_details,
                            SubmissionStatus::label($item->status)
                        ]);
                    }
                    break;
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function downloadImportTemplate($type, SubmissionImporter $importer)
    {
        if (!in_array($type, SubmissionImportRegistry::TYPES, true)) {
            abort(404);
        }

        $this->authorizeSubmission($type, 'import');

        return $importer->downloadTemplate($type);
    }

    public function importSubmissions(Request $request, $type, SubmissionImporter $importer)
    {
        if (!in_array($type, SubmissionImportRegistry::TYPES, true)) {
            abort(404);
        }

        $this->authorizeSubmission($type, 'import');

        $request->validate([
            'import_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
        ]);

        $result = $importer->import($type, $request->file('import_file'));

        if ($result['imported'] === 0 && !empty($result['errors'])) {
            return redirect()
                ->route('welfare.admin.dashboard')
                ->with('import_tab', $request->input('import_tab', 'panel-' . $type))
                ->with('import_errors', $result['errors'])
                ->with('error', 'Import failed. No records were imported.');
        }

        $message = "Successfully imported {$result['imported']} record(s).";
        if ($this->hasStatus($type)) {
            $message .= ' All imported records are set to Received status.';
        }

        if (!empty($result['errors'])) {
            return redirect()
                ->route('welfare.admin.dashboard')
                ->with('import_tab', $request->input('import_tab', 'panel-' . $type))
                ->with('success', $message)
                ->with('import_errors', $result['errors']);
        }

        return redirect()
            ->route('welfare.admin.dashboard')
            ->with('import_tab', $request->input('import_tab', 'panel-' . $type))
            ->with('success', $message);
    }

    private function hasStatus(string $type): bool
    {
        return in_array($type, ['feedback', 'ordinary', 'friends', 'mentor', 'partner', 'volunteer', 'contact', 'aid', 'mfls'], true);
    }
}
