@php
    use App\Support\SubmissionStatus;
    $currentStatus = SubmissionStatus::normalize($item->status ?? SubmissionStatus::default());
    $canUpdateStatus = auth()->user()->hasPermission('submissions.' . $type . '.status');
@endphp
<div class="status-cell">
    @if($canUpdateStatus)
        <select
            class="status-select"
            name="status"
            id="status-{{ $type }}-{{ $item->id }}"
            aria-label="Submission status"
            data-original-value="{{ $currentStatus }}"
            onchange="handleStatusChange(event, '{{ $type }}', {{ $item->id }})"
        >
            @foreach(SubmissionStatus::options() as $value => $label)
                <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    @else
        <span class="status-readonly">{{ SubmissionStatus::label($currentStatus) }}</span>
    @endif
</div>
