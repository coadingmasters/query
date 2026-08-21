/**
 * The blog post editor, as its own entry so the ~500KB of CKEditor only
 * loads on the one page that needs it (resources/admin/posts/form.blade.php),
 * not in the shared app.js bundle every visitor pays for.
 */
import {
    ClassicEditor,
    Essentials,
    Paragraph,
    Heading,
    Bold,
    Italic,
    Underline,
    Strikethrough,
    RemoveFormat,
    Link,
    List,
    Indent,
    IndentBlock,
    BlockQuote,
    Table,
    TableToolbar,
    TableCellProperties,
    TableProperties,
    Image,
    ImageCaption,
    ImageStyle,
    ImageToolbar,
    ImageUpload,
    ImageResize,
    ImageTextAlternative,
    FontColor,
    FontBackgroundColor,
    Alignment,
    HorizontalLine,
    MediaEmbed,
    PasteFromOffice,
    Autoformat,
    TextTransformation,
    SimpleUploadAdapter,
} from 'ckeditor5';

import 'ckeditor5/ckeditor5.css';

window.createPostEditor = function createPostEditor(element, { uploadUrl, csrfToken, initialData } = {}) {
    return ClassicEditor.create(element, {
        licenseKey: 'GPL',
        plugins: [
            Essentials, Paragraph, Heading, Bold, Italic, Underline, Strikethrough, RemoveFormat,
            Link, List, Indent, IndentBlock, BlockQuote, Table, TableToolbar,
            TableCellProperties, TableProperties, Image, ImageCaption, ImageStyle,
            ImageToolbar, ImageUpload, ImageResize, ImageTextAlternative, FontColor, FontBackgroundColor,
            Alignment, HorizontalLine, MediaEmbed, PasteFromOffice, Autoformat, TextTransformation,
            SimpleUploadAdapter,
        ],
        toolbar: {
            items: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', 'removeFormat', '|',
                'fontColor', 'fontBackgroundColor', '|',
                'alignment', '|',
                'bulletedList', 'numberedList', 'outdent', 'indent', '|',
                'link', 'uploadImage', 'insertTable', 'mediaEmbed', 'blockQuote', 'horizontalLine', '|',
                'undo', 'redo',
            ],
            shouldNotGroupWhenFull: true,
        },
        image: {
            toolbar: [
                'imageStyle:inline', 'imageStyle:block', 'imageStyle:side', '|',
                'toggleImageCaption', 'imageTextAlternative', '|',
                'resizeImage',
            ],
        },
        table: {
            contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells', 'tableCellProperties', 'tableProperties'],
        },
        simpleUpload: {
            uploadUrl,
            headers: { 'X-CSRF-TOKEN': csrfToken },
        },
    }).then((editor) => {
        if (initialData) {
            editor.setData(initialData);
        }

        return editor;
    });
};

window.dispatchEvent(new Event('post-editor:ready'));
