<?php
declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

final class Auth
{
    public static function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
        ];
    }

    public static function logout(): never
    {
        session_unset();
        session_destroy();
        redirect(url('auth', 'login'));
    }

    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    public static function id(): int
    {
        return (int) ($_SESSION['user']['id'] ?? 0);
    }

    public static function role(): string
    {
        return $_SESSION['user']['role'] ?? '';
    }

    public static function requireRole(string ...$roles): void
    {
        if (!self::check()) {
            redirect(url('auth', 'login'));
        }
        if (!in_array(self::role(), $roles, true)) {
            redirect(url('errors', '403'));
        }
    }
}

