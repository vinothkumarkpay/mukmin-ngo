<?php

namespace App\Http\Controllers\Welfare;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Welfare\Concerns\ChecksAdminAccess;
use App\Models\SisterhoodRegistration;
use App\Services\Welfare\SisterhoodAgendaReminderSender;
use App\Services\Welfare\SisterhoodQrCode;
use App\Services\Welfare\SisterhoodRegistrationNotifier;
use App\Support\SisterhoodCollective;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminSisterhoodRegistrationController extends Controller
{
    use ChecksAdminAccess;

    public function index(Request $request)
    {
        $this->authorizePermission('sisterhood.view');

        $registrations = $this->filteredRegistrations($request)->paginate(30)->withQueryString();

        return view('welfare.admin.sisterhood.index', [
            'registrations' => $registrations,
            'canManage' => $this->adminUser()->hasPermission('sisterhood.manage'),
            'paidCount' => SisterhoodRegistration::where('status', SisterhoodRegistration::STATUS_SUCCESS)->count(),
            'pendingCount' => SisterhoodRegistration::where('status', SisterhoodRegistration::STATUS_PENDING)->count(),
            'failedCount' => SisterhoodRegistration::where('status', SisterhoodRegistration::STATUS_FAIL)->count(),
            'presentCount' => SisterhoodRegistration::where('status', SisterhoodRegistration::STATUS_SUCCESS)->whereNotNull('attended_at')->count(),
        ]);
    }

    public function export(Request $request)
    {
        $this->authorizePermission('sisterhood.view');

        $registrations = $this->filteredRegistrations($request)->get();
        $headers = [
            'Registered at',
            'Full name',
            'Salutation',
            'Preferred name',
            'NRIC / Passport',
            'Email',
            'Contact number',
            'Age',
            'Profession',
            'Education',
            'Unit number',
            'Address line 1',
            'Address line 2',
            'Postcode',
            'State',
            'Area of interest',
            'Looking forward',
            'Food preference',
            'Emergency contact name',
            'Emergency contact number',
            'Relationship',
            'How they heard',
            'Amount (RM)',
            'Payment status',
            'Record status',
            'Order ID',
            'Payment reference',
            'Paid at',
            'Check-in code',
            'Attendance',
            'Attended at',
            'Admin notes',
        ];

        $rows = [$headers];
        foreach ($registrations as $item) {
            $rows[] = [
                optional($item->created_at)->format('d M Y, h:i A'),
                $item->full_name,
                $item->salutation,
                $item->preferred_name,
                $item->nric_passport,
                $item->email,
                $item->contact_number,
                $item->age,
                $item->professionLabel(),
                $item->educationLabel(),
                $item->unit_number,
                $item->address_line_1,
                $item->address_line_2,
                $item->postcode,
                $item->state,
                $this->exportList($item->areas_of_interest),
                $this->exportList($item->looking_forward),
                $item->food_preference,
                $item->emergency_contact_name,
                $item->emergency_contact_number,
                $item->emergency_relationship,
                $item->heardFromLabel(),
                number_format((float) $item->amount, 2, '.', ''),
                $item->paymentStatusLabel(),
                $item->statusLabel(),
                $item->order_id,
                $item->payment_reference,
                optional($item->paid_at)->format('d M Y, h:i A'),
                $item->check_in_code,
                $item->attendanceLabel(),
                optional($item->attended_at)->format('d M Y, h:i A'),
                $item->admin_notes,
            ];
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Registrations');
        $sheet->fromArray($rows, null, 'A1');
        $sheet->getStyle('A1:AF1')->getFont()->setBold(true);
        $sheet->freezePane('A2');
        $sheet->setAutoFilter($sheet->calculateWorksheetDimension());

        $suffix = $request->filled('payment_status') ? '-' . $request->input('payment_status') : '';
        $filename = 'sisterhood-registrations' . $suffix . '-' . now()->format('Ymd-His') . '.xlsx';
        $temp = tempnam(sys_get_temp_dir(), 'sisterhood-xlsx');
        (new Xlsx($spreadsheet))->save($temp);
        $spreadsheet->disconnectWorksheets();

        return response()->download($temp, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    private function filteredRegistrations(Request $request): Builder
    {
        $query = SisterhoodRegistration::query()->orderByDesc('created_at');

        $paymentStatus = (string) $request->input('payment_status', '');
        if (in_array($paymentStatus, ['pending', 'paid', 'failed'], true)) {
            $query->where('payment_status', $paymentStatus);
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
                    ->orWhere('check_in_code', 'like', $like)
                    ->orWhere('payment_reference', 'like', $like);
            });
        }

        return $query;
    }

    private function exportList($value): string
    {
        if (! is_array($value)) {
            return (string) $value;
        }

        return implode(', ', $value);
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

        $request->merge(SisterhoodCollective::capitaliseInput($request->all()));

        $validated = $request->validate(
            SisterhoodCollective::adminRules(),
            SisterhoodCollective::validationMessages(),
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

        if (! $registration->isTest()) {
            $attributes['status'] = SisterhoodRegistration::statusForPayment($validated['payment_status']);
        }

        $registration->update($attributes);
        $notifier->notifyIfStatusChanged($registration, $previousStatus);

        return redirect()
            ->route('welfare.admin.sisterhood.show', $registration)
            ->with('success', $registration->isPaid()
                ? 'Registration updated. Payment is recorded and the registration is confirmed.'
                : 'Registration updated. It stays incomplete until payment of RM' . number_format(SisterhoodCollective::FEE, 0) . ' is recorded.');
    }

    public function qr(SisterhoodRegistration $registration, SisterhoodQrCode $qrCode)
    {
        $this->authorizePermission('sisterhood.view');

        return response($qrCode->png($registration), 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

    public function attendance()
    {
        $this->authorizePermission('sisterhood.view');

        return view('welfare.admin.sisterhood.attendance', [
            'presentCount' => SisterhoodRegistration::where('status', SisterhoodRegistration::STATUS_SUCCESS)->whereNotNull('attended_at')->count(),
            'paidCount' => SisterhoodRegistration::where('status', SisterhoodRegistration::STATUS_SUCCESS)->count(),
        ]);
    }

    public function reminder(SisterhoodAgendaReminderSender $sender)
    {
        $this->authorizePermission('sisterhood.manage');

        $recipients = $sender->paidRecipients();

        return view('welfare.admin.sisterhood.reminder', [
            'paidCount' => SisterhoodRegistration::where('status', SisterhoodRegistration::STATUS_SUCCESS)->count(),
            'recipientCount' => $recipients->count(),
            'agendaAvailable' => SisterhoodCollective::agendaPdfAvailable(),
            'agendaFilename' => SisterhoodCollective::AGENDA_PDF_FILENAME,
        ]);
    }

    public function sendReminderTest(Request $request, SisterhoodAgendaReminderSender $sender)
    {
        $this->authorizePermission('sisterhood.manage');

        $validated = $request->validate([
            'test_email' => ['required', 'email', 'max:255'],
        ]);

        try {
            $sender->sendTest($validated['test_email']);
        } catch (\Throwable $e) {
            return redirect()
                ->route('welfare.admin.sisterhood.reminder')
                ->withInput()
                ->with('error', 'Test email failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('welfare.admin.sisterhood.reminder')
            ->with('success', 'Test reminder email with agenda attachment sent to ' . $validated['test_email'] . '.');
    }

    public function sendReminderToPaid(Request $request, SisterhoodAgendaReminderSender $sender)
    {
        $this->authorizePermission('sisterhood.manage');

        $request->validate([
            'confirm' => ['accepted'],
        ], [
            'confirm.accepted' => 'Confirm that you want to email all paid participants before sending.',
        ]);

        try {
            $result = $sender->sendToPaid();
        } catch (\Throwable $e) {
            return redirect()
                ->route('welfare.admin.sisterhood.reminder')
                ->with('error', 'Bulk send failed: ' . $e->getMessage());
        }

        $message = 'Agenda reminder sent to ' . $result['sent'] . ' paid participant'
            . ($result['sent'] === 1 ? '' : 's') . '.';

        if ($result['failed'] > 0) {
            $message .= ' ' . $result['failed'] . ' failed.';
            $firstFailures = collect($result['failures'])->take(3)
                ->map(fn ($failure) => $failure['email'] . ' (' . $failure['error'] . ')')
                ->implode('; ');
            if ($firstFailures !== '') {
                $message .= ' Examples: ' . $firstFailures;
            }
        }

        return redirect()
            ->route('welfare.admin.sisterhood.reminder')
            ->with($result['failed'] > 0 && $result['sent'] === 0 ? 'error' : 'success', $message);
    }

    public function markAttendance(Request $request, SisterhoodQrCode $qrCode)
    {
        $this->authorizePermission('sisterhood.view');

        $data = $request->validate([
            'code' => ['required', 'string', 'max:2000'],
            'present' => ['required', 'boolean'],
        ]);

        $code = $qrCode->codeFromScan($data['code']);
        $registration = $code
            ? SisterhoodRegistration::where('check_in_code', $code)->first()
            : null;

        if (! $registration) {
            return $this->attendanceResponse($request, null, false, 'No registration matches this code.', 404);
        }

        $markPresent = $request->boolean('present');

        if ($markPresent && ! $registration->isPaid()) {
            return $this->attendanceResponse(
                $request,
                $registration,
                false,
                'This registration is not paid, so attendance cannot be marked.',
                422
            );
        }

        if ($markPresent) {
            $alreadyPresent = $registration->isPresent();
            if (! $alreadyPresent) {
                $registration->attended_at = now();
                $registration->save();
            }
            $message = $alreadyPresent
                ? $registration->full_name . ' is already marked present.'
                : $registration->full_name . ' is marked present.';
        } else {
            $registration->attended_at = null;
            $registration->save();
            $message = $registration->full_name . ' is marked not present.';
        }

        return $this->attendanceResponse($request, $registration->fresh(), true, $message, 200);
    }

    private function attendanceResponse(Request $request, ?SisterhoodRegistration $registration, bool $ok, string $message, int $status)
    {
        $payload = [
            'ok' => $ok,
            'message' => $message,
            'registration' => $registration ? [
                'name' => $registration->full_name,
                'preferred_name' => $registration->preferred_name,
                'order_id' => $registration->order_id,
                'email' => $registration->email,
                'payment' => $registration->paymentStatusLabel(),
                'attendance' => $registration->attendanceLabel(),
                'present' => $registration->isPresent(),
                'attended_at' => optional($registration->attended_at)->format('d M Y, h:i A'),
                'url' => route('welfare.admin.sisterhood.show', $registration),
            ] : null,
        ];

        if ($request->expectsJson()) {
            return response()->json($payload, $status);
        }

        return redirect()
            ->back()
            ->with($ok ? 'success' : 'error', $message);
    }
}
