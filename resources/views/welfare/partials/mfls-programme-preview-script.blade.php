<script>
window.renderMflsProgrammePreview = function (container, data) {
    if (!container) return;

    container.innerHTML = '';

    var iframe = document.createElement('iframe');
    iframe.className = 'mfls-programme-iframe';
    iframe.setAttribute('sandbox', 'allow-same-origin');
    iframe.setAttribute('title', data.title || 'Programme Information');
    iframe.setAttribute('loading', 'lazy');
    container.appendChild(iframe);

    var doc = iframe.contentDocument || iframe.contentWindow.document;
    doc.open();
    doc.write(
        '<!DOCTYPE html><html><head><meta charset="utf-8"><style>' +
        (data.styles || '') +
        '</style></head><body>' +
        (data.html || '') +
        '</body></html>'
    );
    doc.close();

    var resizeIframe = function () {
        try {
            var height = doc.documentElement.scrollHeight || doc.body.scrollHeight;
            iframe.style.height = Math.min(Math.max(height + 16, 280), 2400) + 'px';
        } catch (e) {
            iframe.style.height = '480px';
        }
    };

    iframe.onload = resizeIframe;
    setTimeout(resizeIframe, 120);
    setTimeout(resizeIframe, 400);
};

window.loadMflsProgrammePreview = function (previewUrl, container, onSuccess, onError) {
    if (!container) return;

    container.innerHTML = '<div class="mfls-programme-modal__loading">Loading programme information...</div>';

    fetch(previewUrl, {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(function (response) {
            if (!response.ok) {
                throw new Error('Preview unavailable');
            }
            return response.json();
        })
        .then(function (data) {
            container.innerHTML = '<div class="mfls-programme-preview"></div>';
            window.renderMflsProgrammePreview(container.querySelector('.mfls-programme-preview'), data);
            if (typeof onSuccess === 'function') {
                onSuccess(data);
            }
        })
        .catch(function () {
            container.innerHTML = '<div class="mfls-programme-modal__error">Programme information is not available for this partner yet.</div>';
            if (typeof onError === 'function') {
                onError();
            }
        });
};
</script>
