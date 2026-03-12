document.addEventListener("DOMContentLoaded", function () {
    if (typeof tinymce === "undefined") return;

    tinymce.init({
        selector: "textarea.editor",
        height: 500,
        plugins: [
            "advlist autolink lists link image charmap preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table code help wordcount"
        ],
        toolbar:
            "undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | " +
            "bullist numlist outdent indent | link image media | code preview fullscreen",
        menubar: true,
        branding: false,
        automatic_uploads: false,
        image_title: true,
        file_picker_types: "image",
        content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:16px }"
    });
});
