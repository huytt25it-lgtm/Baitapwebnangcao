<?php
// Get the document root
$doc_root = $_SERVER['DOCUMENT_ROOT'] ?? '';

// Get the application path
$script_name = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '/index.php');
$app_path = rtrim(dirname($script_name), '/.') . '/';

// Set the include path
set_include_path(dirname(__DIR__) . PATH_SEPARATOR . get_include_path());
?>
