@php
    use App\Support\SubmissionStatus;
    $currentStatus = SubmissionStatus::normalize($item->status ?? SubmissionStatus::default());
@endphp
<div class="status-cell">
    <select
        class="status-select"
        name="status"
        id="status-{{ $type }}-{{ $item->id }}"
        aria-label="Submission status"
        data-original-value="{{ $currentStatus }}"
        onchange="handleStatusChange(event, '{{ $type }}', {{ $item->id }})"
    >
        @foreach(SubmissionStatus::options() as $value => $label)
            <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>
