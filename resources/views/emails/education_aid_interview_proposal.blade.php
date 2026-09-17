<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Interview Scheduling — Education Aid #{{ $submission->id }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #0f172a; line-height: 1.6; padding: 24px;">
    <h2 style="color: #d43c18; margin-bottom: 8px;">Education Aid Interview Scheduling</h2>
    <p>Dear colleague / applicant,</p>
    <p>
        An interview is being scheduled for <strong>Education Aid #{{ $submission->id }}</strong>
        ({{ $submission->full_name }}).
    </p>
    <p>Please review the following proposed dates from {{ $schedulerName }}:</p>
    <ol>
        @foreach($proposal->proposed_dates as $date)
            <li><strong>{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</strong></li>
        @endforeach
    </ol>
    <p>
        Kindly reply to confirm one of the available dates so the MUKMIN team can finalise the interview schedule.
    </p>
    <p style="color: #64748b; font-size: 13px; margin-top: 28px;">
        This is an automated message from the MUKMIN Administrative Portal.
    </p>
</body>
</html>
