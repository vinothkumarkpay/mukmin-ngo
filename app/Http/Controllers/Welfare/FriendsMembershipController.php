<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Welfare\Concerns\ChecksAdminAccess;
use App\Models\FriendMemberSubmission;
use App\Services\Welfare\FriendsMembershipService;
use App\Support\SubmissionStatus;
use Illuminate\Http\Request;

class FriendsMembershipController extends Controller
{
    use ChecksAdminAccess;

    private FriendsMembershipService $service;

    public function __construct(FriendsMembershipService $service)
    {
        $this->service = $service;
    }

    public function updateWelcomeEmailSetting(Request $request)
    {
        $this->authorizeSubmission('friends', 'status');

        $validated = $request->validate([
            'enabled' => 'required|boolean',
        ]);

        $this->service->setWelcomeEmailEnabled((bool) $validated['enabled'], optional($this->adminUser())->id);

        return response()->json([
            'success' => true,
            'enabled' => (bool) $validated['enabled'],
        ]);
    }

    public function letter($id)
    {
        $this->authorizeSubmission('friends', 'view');
        $member = $this->approvedMember($id);

        return response($this->service->letterPdf($member), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $this->service->letterFilename($member) . '"',
        ]);
    }

    public function card($id)
    {
        $this->authorizeSubmission('friends', 'view');
        $member = $this->approvedMember($id);

        return response($this->service->cardPdf($member), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $this->service->cardFilename($member) . '"',
        ]);
    }

    public function resendWelcomeEmail($id)
    {
        $this->authorizeSubmission('friends', 'status');
        $member = $this->approvedMember($id);

        try {
            $this->service->sendWelcomeEmail($member);
        } catch (\RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Failed to send the welcome email.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Welcome email with membership letter and e-card sent to ' . $member->member_email . '.',
        ]);
    }

    private function approvedMember($id): FriendMemberSubmission
    {
        $member = FriendMemberSubmission::findOrFail($id);

        if (SubmissionStatus::normalize($member->status) !== SubmissionStatus::APPROVED) {
            abort(422, 'Membership documents are only available for approved members.');
        }

        return $this->service->ensureMembership($member);
    }
}
