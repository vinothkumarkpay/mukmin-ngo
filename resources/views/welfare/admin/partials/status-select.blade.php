@php
    use App\Support\EducationAidStatus;
    use App\Support\SubmissionStatus;

    $isAid = ($type ?? '') === 'aid';
    $currentStatus = $isAid
        ? EducationAidStatus::normalize($item->status ?? EducationAidStatus::default())
        : SubmissionStatus::normalize($item->status ?? SubmissionStatus::default());
    $statusOptions = $isAid ? EducationAidStatus::options() : SubmissionStatus::options();
    $canUpdateStatus = auth()->user()->hasPermission('submissions.' . $type . '.status');
    $readonlyLabel = $isAid
        ? EducationAidStatus::label($currentStatus)
        : SubmissionStatus::label($currentStatus);
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
            @foreach($statusOptions as $value => $label)
                <option value="{{ $value }}" {{ $currentStatus === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    @else
        <span class="status-readonly">{{ $readonlyLabel }}</span>
    @endif
</div>
