<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.letters-only-input').forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/[^\p{L}\s]/gu, '');
        });
    });
});
</script>
