<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Welfare\Concerns\ChecksAdminAccess;
use App\Models\SisterhoodRegistration;
use App\Services\Welfare\SisterhoodRegistrationNotifier;
use App\Support\SisterhoodCollective;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminSisterhoodRegistrationController extends Controller
{
    use ChecksAdminAccess;

    public function index(Request $request)
    {
        $this->authorizePermission('sisterhood.view');

        $query = SisterhoodRegistration::query()->orderByDesc('created_at');

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $search = trim((string) $request->input('search', ''));
        if ($search !== '') {
            $like = '%' . addcslashes($search, '%_\\') . '%';
            $query->where(function ($inner) use ($like) {
                $inner->where('full_name', 'like', $like)
                    ->orWhere('preferred_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('contact_number', 'like', $like)
                    ->orWhere('nric_passport', 'like', $like)
                    ->orWhere('order_id', 'like', $like)
                    ->orWhere('payment_reference', 'like', $like);
            });
        }

        $registrations = $query->paginate(30)->withQueryString();

        return view('welfare.admin.sisterhood.index', [
            'registrations' => $registrations,
            'canManage' => $this->adminUser()->hasPermission('sisterhood.manage'),
            'paidCount' => SisterhoodRegistration::where('payment_status', 'paid')->count(),
            'pendingCount' => SisterhoodRegistration::where('payment_status', 'pending')->count(),
            'failedCount' => SisterhoodRegistration::where('payment_status', 'failed')->count(),
        ]);
    }

    public function show(SisterhoodRegistration $registration)
    {
        $this->authorizePermission('sisterhood.view');

        return view('welfare.admin.sisterhood.show', [
            'registration' => $registration,
            'canManage' => $this->adminUser()->hasPermission('sisterhood.manage'),
        ]);
    }

    public function edit(SisterhoodRegistration $registration)
    {
        $this->authorizePermission('sisterhood.manage');

        return view('welfare.admin.sisterhood.edit', [
            'registration' => $registration,
        ]);
    }

    public function update(Request $request, SisterhoodRegistration $registration, SisterhoodRegistrationNotifier $notifier)
    {
        $this->authorizePermission('sisterhood.manage');
        $previousStatus = (string) $registration->payment_status;

        $validated = $request->validate(
            SisterhoodCollective::adminRules(),
            [],
            SisterhoodCollective::attributeNames()
        );

        if (SisterhoodCollective::hasPaidRegistration((string) $validated['nric_passport'], $registration->id)
            && $validated['payment_status'] === 'paid') {
            throw ValidationException::withMessages([
                'nric_passport' => 'Another confirmed registration already uses this NRIC or passport number.',
            ]);
        }

        $attributes = SisterhoodCollective::attributesFrom($validated);
        $attributes['payment_status'] = $validated['payment_status'];
        $attributes['payment_reference'] = $validated['payment_reference'] ?? null;
        $attributes['admin_notes'] = $validated['admin_notes'] ?? null;
        $attributes['paid_at'] = $validated['payment_status'] === 'paid'
            ? ($registration->paid_at ?? now())
            : null;

        $registration->update($attributes);
        $notifier->notifyIfStatusChanged($registration, $previousStatus);

        return redirect()
            ->route('welfare.admin.sisterhood.show', $registration)
            ->with('success', $registration->isPaid()
                ? 'Registration updated. Payment is recorded and the registration is confirmed.'
                : 'Registration updated. It stays incomplete until payment of RM30 is recorded.');
    }
}
