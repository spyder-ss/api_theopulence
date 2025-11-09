document.addEventListener('DOMContentLoaded', function() {
    // Find all textareas with the .ckeditor class
    const editors = document.querySelectorAll('textarea.ckeditor');
    
    editors.forEach(editor => {
        // Check if the editor has not already been initialized
        if (!editor.hasAttribute('data-ckeditor-initialized')) {
            CKEDITOR.replace(editor.id, {
                customConfig: '/js/ckeditor_config.js'
            });
            // Mark the editor as initialized
            editor.setAttribute('data-ckeditor-initialized', 'true');
        }
    });
});
