@if(config('welfare.whatsapp_url'))
<a href="{{ config('welfare.whatsapp_url') }}"
   class="whatsapp-support-fab"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Chat with us on WhatsApp"
   title="WhatsApp Support">
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
</a>
@endif
