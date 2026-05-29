@once
@push('styles')
<style>
.important-notes {
    background: #fffcfb;
    border-left: 4px solid #d43c18;
    padding: 20px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 30px;
}
.important-notes h4 {
    color: #b83210;
    font-size: 14px;
    font-weight: 700;
    margin: 0 0 10px 0;
}
.important-notes ol {
    margin: 0;
    padding-left: 18px;
}
.important-notes li {
    font-size: 13px;
    color: #666;
    margin-bottom: 6px;
    line-height: 18px;
}
.important-notes li:last-child {
    margin-bottom: 0;
}
</style>
@endpush
@endonce

@php
    $items = $items ?? [
        'Incomplete forms may not be processed.',
        'MUKMIN reserves the right to approve or reject any application.',
        'Additional supporting documents may be requested if necessary.',
    ];
@endphp

<div class="important-notes">
    <h4>IMPORTANT NOTES</h4>
    <ol>
        @foreach ($items as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ol>
</div>
