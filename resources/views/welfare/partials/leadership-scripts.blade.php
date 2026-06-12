<script>
document.addEventListener('DOMContentLoaded', function () {
    var tabBtns = document.querySelectorAll('.leadership-tab-btn');
    var tabContents = document.querySelectorAll('.leadership-tab-content');
    var memberCards = document.querySelectorAll('.member-card[data-member-name]');
    var hoverPreview = document.getElementById('memberHoverPreview');
    var hoverPreviewImage = document.getElementById('memberHoverPreviewImage');
    var modal = document.getElementById('leadershipMemberModal');
    var canHover = window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    var showTimer = null;
    var hideTimer = null;
    var activeCard = null;

    tabBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            tabBtns.forEach(function (b) { b.classList.remove('active'); });
            tabContents.forEach(function (c) { c.classList.remove('active'); });
            this.classList.add('active');

            var targetContent = document.getElementById(this.getAttribute('data-target'));
            if (targetContent) {
                targetContent.classList.add('active');
            }

            hideHoverPreview(true);
        });
    });

    function readMemberData(card) {
        return {
            name: card.getAttribute('data-member-name') || '',
            role: card.getAttribute('data-member-role') || '',
            org: card.getAttribute('data-member-org') || '',
            tag: card.getAttribute('data-member-tag') || '',
            image: card.getAttribute('data-member-image') || '',
            committee: card.getAttribute('data-member-committee') || ''
        };
    }

    function buildMetaText(data) {
        if (data.tag) {
            return data.tag;
        }

        if (data.org) {
            return data.org;
        }

        return '';
    }

    function setImageSrc(img, src) {
        if (!img || !src || img.getAttribute('src') === src) {
            return;
        }

        img.setAttribute('src', src);
    }

    function fillProfileTargets(data, targets) {
        setImageSrc(targets.image, data.image);
        targets.image.alt = data.name;
        targets.committee.textContent = data.committee;
        targets.name.textContent = data.name;
        targets.role.textContent = data.role;
        targets.role.style.display = data.role ? 'block' : 'none';

        var meta = buildMetaText(data);
        targets.meta.textContent = meta;
        targets.meta.style.display = meta ? 'block' : 'none';
    }

    function positionHoverPreview(card) {
        if (!hoverPreview || !card) {
            return;
        }

        var rect = card.getBoundingClientRect();
        var previewRect = hoverPreview.getBoundingClientRect();
        var gap = 14;
        var top = rect.top - previewRect.height - gap;
        var left = rect.left + (rect.width / 2) - (previewRect.width / 2);

        if (top < 12) {
            top = rect.bottom + gap;
        }

        left = Math.max(12, Math.min(left, window.innerWidth - previewRect.width - 12));
        top = Math.max(12, Math.min(top, window.innerHeight - previewRect.height - 12));

        hoverPreview.style.top = top + 'px';
        hoverPreview.style.left = left + 'px';
    }

    function showHoverPreview(card, data) {
        if (!hoverPreview || !canHover || activeCard === card) {
            return;
        }

        fillProfileTargets(data, {
            image: hoverPreviewImage,
            committee: document.getElementById('memberHoverPreviewCommittee'),
            name: document.getElementById('memberHoverPreviewName'),
            role: document.getElementById('memberHoverPreviewRole'),
            meta: document.getElementById('memberHoverPreviewMeta')
        });

        activeCard = card;
        positionHoverPreview(card);
        hoverPreview.classList.add('visible');
        hoverPreview.setAttribute('aria-hidden', 'false');
    }

    function hideHoverPreview(immediate) {
        if (!hoverPreview) {
            return;
        }

        clearTimeout(showTimer);
        clearTimeout(hideTimer);

        if (immediate) {
            hoverPreview.classList.remove('visible');
            hoverPreview.setAttribute('aria-hidden', 'true');
            activeCard = null;
            return;
        }

        hideTimer = setTimeout(function () {
            hoverPreview.classList.remove('visible');
            hoverPreview.setAttribute('aria-hidden', 'true');
            activeCard = null;
        }, 120);
    }

    function openMemberModal(data) {
        if (!modal) {
            return;
        }

        hideHoverPreview(true);

        fillProfileTargets(data, {
            image: document.getElementById('leadershipMemberModalImage'),
            committee: document.getElementById('leadershipMemberModalCommittee'),
            name: document.getElementById('leadershipMemberModalName'),
            role: document.getElementById('leadershipMemberModalRole'),
            meta: document.getElementById('leadershipMemberModalMeta')
        });

        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.classList.add('leadership-modal-open');
    }

    function closeMemberModal() {
        if (!modal) {
            return;
        }

        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('leadership-modal-open');
    }

    memberCards.forEach(function (card) {
        card.addEventListener('click', function () {
            openMemberModal(readMemberData(card));
        });

        if (canHover) {
            card.addEventListener('mouseenter', function () {
                var currentCard = card;
                clearTimeout(hideTimer);
                clearTimeout(showTimer);

                if (activeCard === currentCard && hoverPreview.classList.contains('visible')) {
                    return;
                }

                showTimer = setTimeout(function () {
                    showHoverPreview(currentCard, readMemberData(currentCard));
                }, 280);
            });

            card.addEventListener('mouseleave', function () {
                clearTimeout(showTimer);
                hideHoverPreview(false);
            });
        }
    });

    if (hoverPreview && canHover) {
        hoverPreview.addEventListener('mouseenter', function () {
            clearTimeout(hideTimer);
        });

        hoverPreview.addEventListener('mouseleave', function () {
            hideHoverPreview(false);
        });

        hoverPreview.addEventListener('click', function () {
            if (!activeCard) {
                return;
            }

            openMemberModal(readMemberData(activeCard));
        });
    }

    if (modal) {
        modal.querySelectorAll('[data-close-modal]').forEach(function (el) {
            el.addEventListener('click', closeMemberModal);
        });
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeMemberModal();
            hideHoverPreview(true);
        }
    });

    window.addEventListener('scroll', function () {
        hideHoverPreview(true);
    }, true);

    window.addEventListener('resize', function () {
        hideHoverPreview(true);
    });
});
</script>
