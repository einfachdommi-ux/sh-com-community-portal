document.addEventListener('DOMContentLoaded', function () {
    if (typeof tinymce === 'undefined') {
        return;
    }

    tinymce.init({
        selector: '.html-editor',
        height: 500,
        menubar: true,
        plugins: 'anchor autolink charmap code codesample fullscreen help image insertdatetime link lists media preview searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | code preview fullscreen',
        branding: false,
        promotion: false,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true
    });
});
