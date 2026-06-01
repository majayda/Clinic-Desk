<?php
declare(strict_types=1);

function url(string $page = 'dashboard', string $action = 'index', array $params = []): string
{
    $query = array_merge(['page' => $page, 'action' => $action], $params);
    return 'index.php?' . http_build_query($query);
}

function redirect(string $target): never
{
    header('Location: ' . $target);
    exit;
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function formatDate(?string $date): string
{
    return $date ? date('Y-m-d', strtotime($date)) : '';
}

function formatTime(?string $time): string
{
    return $time ? date('H:i', strtotime($time)) : '';
}

function active(string $page, ?string $action = null): string
{
    $currentPage = $_GET['page'] ?? 'dashboard';
    $currentAction = $_GET['action'] ?? 'index';

    if ($currentPage !== $page) {
        return '';
    }

    if ($action !== null && $currentAction !== $action) {
        return '';
    }

    return 'active';
}

function statusBadge(string $status): string
{
    $classes = [
        'pending' => 'badge-warning',
        'confirmed' => 'badge-info',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger',
    ];

    $class = $classes[$status] ?? 'badge-secondary';
    return '<span class="badge ' . $class . '">' . e($status) . '</span>';
}

function require_post_csrf(): void
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !CSRF::validateToken($_POST['csrf_token'] ?? '')) {
        flash('danger', 'Invalid security token. Please try again.');
        redirect($_SERVER['HTTP_REFERER'] ?? url());
    }
}

function upload_image(string $field, string $directory, string $prefix): ?string
{
    if (empty($_FILES[$field]['name'])) {
        return null;
    }

    if (($_FILES[$field]['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        flash('danger', 'Image upload failed. Please try again.');
        redirect($_SERVER['HTTP_REFERER'] ?? url());
    }

    if ((int) $_FILES[$field]['size'] > MAX_IMAGE_SIZE) {
        flash('danger', 'Images must be 1MB or less.');
        redirect($_SERVER['HTTP_REFERER'] ?? url());
    }

    $info = getimagesize($_FILES[$field]['tmp_name']);
    if ($info === false || !in_array($info['mime'], ['image/jpeg', 'image/png'], true)) {
        flash('danger', 'Only valid JPEG or PNG images are allowed.');
        redirect($_SERVER['HTTP_REFERER'] ?? url());
    }

    $extension = $info['mime'] === 'image/png' ? 'png' : 'jpg';
    $name = $prefix . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
    $target = __DIR__ . '/../public/uploads/' . $directory . '/' . $name;

    if (!move_uploaded_file($_FILES[$field]['tmp_name'], $target)) {
        flash('danger', 'Could not save uploaded image.');
        redirect($_SERVER['HTTP_REFERER'] ?? url());
    }

    return $name;
}
