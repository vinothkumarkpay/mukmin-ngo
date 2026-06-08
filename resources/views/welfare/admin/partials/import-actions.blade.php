<a href="{{ route('welfare.admin.import.template', $type) }}" class="btn-admin btn-admin-secondary" title="Download Excel import template">
    <i class="fas fa-file-excel"></i> Download Template
</a>
<form action="{{ route('welfare.admin.import', $type) }}" method="POST" enctype="multipart/form-data" class="admin-import-form">
    @csrf
    <input type="hidden" name="import_tab" value="panel-{{ $type }}">
    <label class="admin-import-file-label">
        <input type="file" name="import_file" accept=".xlsx,.xls,.csv,.txt" required class="admin-import-file-input">
        <span class="btn-admin btn-admin-secondary admin-import-file-btn"><i class="fas fa-folder-open"></i> Choose File</span>
    </label>
    <button type="submit" class="btn-admin btn-admin-primary">
        <i class="fas fa-upload"></i> Import
    </button>
</form>
