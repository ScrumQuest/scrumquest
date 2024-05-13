import 'tinymce/tinymce';
import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';
import 'tinymce/skins/content/default/content.css';
import 'tinymce/icons/default/icons';
import 'tinymce/themes/silver/theme';
import 'tinymce/models/dom/model';
import 'tinymce/plugins/image/plugin.min.js';
import 'tinymce/plugins/code/plugin.min.js';
import 'tinymce/plugins/lists/plugin.min.js';
import 'tinymce/plugins/advlist/plugin.min.js';
import 'tinymce/plugins/link/plugin.min.js';
import 'tinymce/plugins/autolink/plugin.min.js';
import 'tinymce/plugins/preview/plugin.min.js';
import 'tinymce/plugins/table/plugin.min.js';
import 'tinymce/plugins/codesample/plugin.min.js';
import 'tinymce/plugins/fullscreen/plugin.min.js';

window.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: 'textarea.editable',
        menubar: false,
        statusbar: false,
        plugins: [
            'advlist', 'autolink', 'link', 'image', 'lists', 'preview', 'table', 'codesample', 'code', 'fullscreen',
        ],
        toolbar: 'fullscreen | undo redo | styles | bold italic underline | superscript subscript | table | codesample code | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | preview | forecolor backcolor',

        toolbar_groups: {
            table: {
                icon: 'table',
                tooltip: 'Table',
                items: 'tableinsertdialog tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol'
            }
        },

        /* enable title field in the Image dialog*/
        image_title: false,
        /* enable automatic uploads of images represented by blob or data URIs*/
        automatic_uploads: true,
        /*
          URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
          images_upload_url: 'postAcceptor.php',
          here we add custom filepicker only to Image dialog
        */
        file_picker_types: 'image',
        /* and here's our custom image picker*/
        file_picker_callback: (cb, value, meta) => {
            const input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];

                const reader = new FileReader();
                reader.addEventListener('load', () => {
                    /*
                      Note: Now we need to register the blob in TinyMCEs image blob
                      registry. In the next release this part hopefully won't be
                      necessary, as we are looking to handle it internally.
                    */
                    const id = 'blobid' + (new Date()).getTime();
                    const blobCache = tinymce.activeEditor.editorUpload.blobCache;
                    const base64 = reader.result.split(',')[1];
                    const blobInfo = blobCache.create(id, file, base64);
                    blobCache.add(blobInfo);

                    /* call the callback and populate the Title field with the file name */
                    cb(blobInfo.blobUri(), {title: file.name});
                });
                reader.readAsDataURL(file);
            });

            input.click();
        },
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });

    tinymce.init({
        selector: 'textarea',
        readonly: true,
        menubar: false,
        toolbar: false,
        statusbar: false,

        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }',
    });
});
