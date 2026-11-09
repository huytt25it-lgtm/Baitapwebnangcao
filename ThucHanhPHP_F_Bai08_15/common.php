<?php
declare(strict_types=1);

function e(string|int|float|null $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function field(string $key, array $source = []): string
{
    $value = $source[$key] ?? '';
    return is_string($value) ? trim($value) : '';
}

function posted(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function integer(string $value, int $min, int $max): int|false
{
    if (!preg_match('/^[+-]?[0-9]+$/D', $value)) {
        return false;
    }
    $number = (float) $value;
    return $number >= $min && $number <= $max ? (int) $value : false;
}

function numeric(string $value): bool
{
    return is_numeric($value) && is_finite((float) $value);
}

function displayNumber(int|float $number): string
{
    return (string) round($number, 8);
}

function page(string $title, string $intro = ''): void
{
    ?>
<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($title) ?> · Thực hành PHP</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body><main>
    <nav><a href="../">← Danh mục Bài 8–15</a></nav>
    <h1><?= e($title) ?></h1>
    <?php if ($intro !== ''): ?><p><?= e($intro) ?></p><?php endif ?>
    <?php
}

function endPage(): void
{
    echo '</main></body></html>';
}

function input(string $label, string $name, string $value = '', string $type = 'text'): void
{
    ?>
    <label for="<?= e($name) ?>"><?= e($label) ?></label>
    <input id="<?= e($name) ?>" name="<?= e($name) ?>" type="<?= e($type) ?>" value="<?= e($value) ?>" required
        <?= $type === 'number' ? 'step="any"' : '' ?>>
    <?php
}

function result(string $label, string|int|float $value): void
{
    ?>
    <label for="kq-<?= md5($label) ?>"><?= e($label) ?></label>
    <input id="kq-<?= md5($label) ?>" value="<?= e($value) ?>" readonly>
    <?php
}

function message(string $text, bool $error = false): void
{
    if ($text !== '') {
        echo '<p class="' . ($error ? 'error' : 'notice') . '" role="status">' . e($text) . '</p>';
    }
}

function openSession(): void
{
    session_name('PHPFSESSID');
    session_start(['cookie_httponly' => true, 'cookie_samesite' => 'Lax', 'use_strict_mode' => true]);
}

function csrf(): string
{
    return $_SESSION['phpf_token'] ??= bin2hex(random_bytes(32));
}

function csrfField(): void
{
    echo '<input type="hidden" name="token" value="' . e(csrf()) . '">';
}

function checkCsrf(): void
{
    if (!hash_equals(csrf(), field('token', $_POST))) {
        http_response_code(403);
        exit('Phiên biểu mẫu đã hết hạn. Hãy tải lại trang.');
    }
}

function redirect(string $url = './'): never
{
    header('Location: ' . $url, true, 303);
    exit;
}

