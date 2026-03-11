document.addEventListener('DOMContentLoaded', function () {
    if (typeof tinymce === 'undefined') {
        return;
    }

    tinymce.init({
        selector: 'textarea.editor',
        height: 520,
        menubar: true,
        branding: false,
        promotion: false,
        plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime media table help wordcount',
        toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link table image media | code preview fullscreen',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });
});
