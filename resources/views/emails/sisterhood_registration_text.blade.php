@if($statusKey === 'success')
Assalamu alaikum {{ $registration->full_name }},

Thank you for registering for The Sisterhood Collective 2026.

Your registration and payment of RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} have been successfully received. We look forward to welcoming you to the programme.

Your registration details and the event timing are below. Further details regarding the event will be shared with you in due course.

See you there!
@else
Assalamu alaikum {{ $registration->full_name }},

Your registration has not been completed as payment of RM{{ number_format(\App\Support\SisterhoodCollective::FEE, 0) }} has not been successfully received.

Please try again to complete your payment. Your registration will only be confirmed once payment has been successfully processed.

If you have already made the payment but are still seeing this message, please contact {{ \App\Support\SisterhoodCollective::SUPPORT_PHONE }} for assistance.
@endif

Payment status: {{ $statusLabel }}

Your registration
@foreach($registrationRows as $row)
{{ $row['label'] }}: {{ $row['value'] }}
@endforeach

@if($statusKey === 'success' && filled($registration->check_in_code))
Your entry code
Show this code at the hall on {{ \App\Support\SisterhoodCollective::EVENT_DATE }}.
Check-in code: {{ $registration->check_in_code }}

@endif
Event details
@foreach($eventRows as $row)
{{ $row['label'] }}: {{ $row['value'] }}
@endforeach

Pertubuhan Gabungan MUKMIN Nasional
Support: {{ \App\Support\SisterhoodCollective::SUPPORT_PHONE }}
Reply: {{ config('welfare.email') }}
