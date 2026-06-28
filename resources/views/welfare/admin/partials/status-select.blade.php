@php
    use App\Support\SubmissionStatus;
    $currentStatus = SubmissionStatus::normalize($item->status ?? SubmissionStatus::default());
@endphp
<div class="status-cell">
    <form class="status-form" onsubmit="submitStatusUpdate(event, '{{ $type }}', {{ $item->id }})">
        <select
            class="status-select"
            name="status"
            id="status-{{ $type }}-{{ $item->id }}"
            aria-label="Submission status"
        >
            @foreach(SubmissionStatus::options() as $value => $label)
                <option value="{{ $value }}" @selected($currentStatus === $value)>{{ $label }}</option>
            @endforeach
        </select>
        <button type="submit" class="status-update-btn" title="Save status" aria-label="Save status">
            <i class="fas fa-check"></i>
        </button>
    </form>
</div>
