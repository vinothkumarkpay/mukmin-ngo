@extends('welfare.layouts.admin')

@section('title', 'Donation Payments - MUKMIN Admin')

@section('body')
<div class="dashboard-wrapper">
    @include('welfare.admin.partials.admin-sidebar', [
        'sidebarContext' => 'donation-payments',
        'activeTab' => 'panel-payments',
    ])

    <main class="main-content">
        @include('welfare.admin.partials.admin-top-nav', ['pageTitle' => 'Donation Payments'])

        <div class="content-body">
            <div class="dashboard-card">
                <div class="card-header">
                    <h3>Donation Payments</h3>
                </div>
                <div class="card-body">
                    @include('welfare.admin.partials.donation-payments-content', [
                        'donationPayments' => $donationPayments,
                        'donationPaymentMethods' => $donationPaymentMethods,
                    ])
                </div>
            </div>
        </div>
    </main>
</div>

<div class="modal-backdrop" id="detail-modal" onclick="closeDonationModal()">
    <div class="modal-window" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modal-title">Donation Payment Details</h3>
            <button type="button" class="modal-close" onclick="closeDonationModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" id="modal-body">Loading details...</div>
        <div class="modal-footer">
            <button type="button" class="btn-admin btn-admin-secondary" onclick="closeDonationModal()">Close</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebarBackdrop = document.getElementById('sidebar-backdrop');

    function openSidebar() {
        document.body.classList.add('sidebar-open');
        if (sidebarToggle) sidebarToggle.setAttribute('aria-expanded', 'true');
    }

    function closeSidebar() {
        document.body.classList.remove('sidebar-open');
        if (sidebarToggle) sidebarToggle.setAttribute('aria-expanded', 'false');
    }

    function toggleSidebar() {
        if (document.body.classList.contains('sidebar-open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }
    if (sidebarBackdrop) {
        sidebarBackdrop.addEventListener('click', closeSidebar);
    }

    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeSidebar();
        }
    });

    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&#039;');
    }

    function formatDate(dateStr) {
        if (!dateStr) return '';
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function viewDonationDetail(id) {
        const modal = document.getElementById('detail-modal');
        const modalBody = document.getElementById('modal-body');
        const modalTitle = document.getElementById('modal-title');

        modalBody.innerHTML = '<div style="padding: 20px; text-align: center;"><i class="fas fa-spinner fa-spin fa-2x" style="color: var(--admin-primary);"></i><p style="margin-top:10px;">Loading details...</p></div>';
        modalTitle.textContent = 'Donation Payment Details (#' + id + ')';
        modal.classList.add('open');

        fetch(`{{ url('/admin/submissions') }}/donation/${id}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response not ok');
                return response.json();
            })
            .then(data => {
                let html = '<div class="detail-grid">';
                html += `<div class="detail-label">Date Submitted</div><div class="detail-value">${formatDate(data.created_at)}</div>`;

                for (let key in data) {
                    if (['id', 'created_at', 'updated_at', 'status', 'payment_payload'].includes(key)) continue;

                    let label = key.replace(/_/g, ' ').toUpperCase();
                    let val = data[key];

                    if (val === null || val === undefined) {
                        val = '-';
                    } else if (typeof val === 'object') {
                        val = JSON.stringify(val);
                    }

                    html += `<div class="detail-label">${label}</div><div class="detail-value">${escapeHtml(val).replace(/\n/g, '<br>')}</div>`;
                }

                if (data.payment_payload && typeof data.payment_payload === 'object') {
                    html += `<div class="detail-label">GATEWAY RESPONSE</div><div class="detail-value"><pre style="margin:0; white-space:pre-wrap; font-size:12px;">${escapeHtml(JSON.stringify(data.payment_payload, null, 2))}</pre></div>`;
                }

                if (data.status) {
                    html += `<div class="detail-label">PAYMENT STATUS</div><div class="detail-value"><span class="badge badge-${data.status}">${escapeHtml(data.status.charAt(0).toUpperCase() + data.status.slice(1))}</span></div>`;
                }

                html += '</div>';
                modalBody.innerHTML = html;
            })
            .catch(error => {
                modalBody.innerHTML = `<div class="alert-admin alert-admin-error" style="margin-bottom:0;"><i class="fas fa-exclamation-triangle"></i> Failed to load details: ${error.message}</div>`;
            });
    }

    function closeDonationModal() {
        document.getElementById('detail-modal').classList.remove('open');
    }

    window.viewDetail = function(type, id) {
        if (type === 'donation') {
            viewDonationDetail(id);
        }
    };

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDonationModal();
            closeSidebar();
        }
    });
</script>
@endpush
