<?php
// Lớp PHP nhỏ để minh họa cách thứ hai trong đề.
class CKEditor
{
    public function replaceAll(): void
    {
        echo "<script>if (window.CKEDITOR) CKEDITOR.replaceAll('editor');</script>";
    }
}

