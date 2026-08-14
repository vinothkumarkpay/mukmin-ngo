@php
    /** @var array<string, int> $breakdown */
    $breakdown = $breakdown ?? [];
    $kind = $kind ?? 'submission';

    $meta = $kind === 'donation'
        ? [
            'pending' => 'Pending',
            'paid' => 'Paid',
            'failed' => 'Failed',
        ]
        : [
            \App\Support\SubmissionStatus::RECEIVED => \App\Support\SubmissionStatus::label(\App\Support\SubmissionStatus::RECEIVED),
            \App\Support\SubmissionStatus::REVIEWING => \App\Support\SubmissionStatus::label(\App\Support\SubmissionStatus::REVIEWING),
            \App\Support\SubmissionStatus::PENDING_APPROVAL => \App\Support\SubmissionStatus::label(\App\Support\SubmissionStatus::PENDING_APPROVAL),
            \App\Support\SubmissionStatus::FURTHER_INFO_REQUIRED => \App\Support\SubmissionStatus::label(\App\Support\SubmissionStatus::FURTHER_INFO_REQUIRED),
            \App\Support\SubmissionStatus::APPROVED => \App\Support\SubmissionStatus::label(\App\Support\SubmissionStatus::APPROVED),
            \App\Support\SubmissionStatus::REJECTED => \App\Support\SubmissionStatus::label(\App\Support\SubmissionStatus::REJECTED),
            \App\Support\SubmissionStatus::COMPLETED => \App\Support\SubmissionStatus::label(\App\Support\SubmissionStatus::COMPLETED),
        ];

    $visible = [];
    foreach ($meta as $status => $label) {
        $count = (int) ($breakdown[$status] ?? 0);
        if ($count > 0) {
            $visible[$status] = ['label' => $label, 'count' => $count];
        }
    }
@endphp
@if(count($visible) > 0)
<div class="stat-status-stack" aria-label="Status breakdown">
    @foreach($visible as $status => $item)
        <button
            type="button"
            class="stat-status-chip stat-status-chip--{{ $status }}"
            data-label="{{ $item['label'] }}: {{ $item['count'] }}"
            aria-label="{{ $item['label'] }}: {{ $item['count'] }}"
        >{{ $item['count'] }}</button>
    @endforeach
</div>
@endif
