<a href="{{ route('welfare.admin.friends.membership-letter', $item->id) }}" target="_blank" rel="noopener" class="btn-admin btn-admin-secondary" title="View membership confirmation letter (PDF)"><i class="fas fa-file-pdf"></i> Letter</a>
<a href="{{ route('welfare.admin.friends.membership-card', $item->id) }}" target="_blank" rel="noopener" class="btn-admin btn-admin-secondary" title="View membership e-card (PDF)"><i class="fas fa-id-card"></i> E-Card</a>
@if(auth()->user()->hasPermission('submissions.friends.status'))
    <button type="button" onclick="resendFriendsWelcomeEmail({{ $item->id }}, this)" class="btn-admin btn-admin-secondary" title="{{ $item->welcome_email_sent_at ? 'Welcome email sent ' . $item->welcome_email_sent_at->format('d M Y H:i') . ' - click to resend' : 'Send welcome email with letter and e-card' }}"><i class="fas fa-envelope"></i></button>
@endif
