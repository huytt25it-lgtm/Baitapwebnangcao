<?php
require __DIR__ . '/../common.php';
openSession();
$code = $_SESSION['captcha'][field('id', $_GET)] ?? null;
session_write_close();
header('Cache-Control: no-store');
if ($code === null) {
    http_response_code(404);
    exit;
}

if (extension_loaded('gd')) {
    header('Content-Type: image/png');
    $image = imagecreate(180, 56);
    imagecolorallocate($image, 239, 246, 252);
    $ink = imagecolorallocate($image, 19, 44, 70);
    $noise = imagecolorallocate($image, 150, 172, 192);
    imageline($image, 0, 13, 179, 43, $noise);
    imageline($image, 0, 39, 179, 11, $noise);
    for ($i = 0; $i < 6; $i++) {
        imagestring($image, 5, 24 + 23 * $i, random_int(16, 28), $code[$i], $ink);
    }
    imagepng($image);
    imagedestroy($image);
    exit;
}

// SVG giúp thực hành cùng luồng Session trên máy chưa bật GD.
header('Content-Type: image/svg+xml; charset=UTF-8');
?>
<svg xmlns="http://www.w3.org/2000/svg" width="180" height="56" viewBox="0 0 180 56">
    <rect width="180" height="56" rx="6" fill="#eff6fc"/>
    <path d="M0 13L180 43M0 39L180 11" stroke="#96acc0" fill="none"/>
    <text x="23" y="37" font-family="monospace" font-weight="bold" font-size="24" letter-spacing="7" fill="#132c46"><?= e($code) ?></text>
</svg>

