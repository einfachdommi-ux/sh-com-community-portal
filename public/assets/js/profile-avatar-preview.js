document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('avatarInput');
    const preview = document.getElementById('avatarPreview');

    if (!input || !preview) return;

    input.addEventListener('change', function () {
        const file = input.files && input.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            if (e.target && e.target.result) {
                preview.src = e.target.result;
            }
        };
        reader.readAsDataURL(file);
    });
});
