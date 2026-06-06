<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../models/UserModel.php';

final class AuthController extends BaseController
{
    public function login(): void
    {
        if (Auth::check()) {
            redirect(url());
        }
        $this->view('auth/login', ['pageTitle' => 'Login']);
    }

    public function authenticate(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url('auth', 'login'));
        }

        require_post_csrf();

        try {
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $user = (new UserModel())->findByEmail($email);
        } catch (RuntimeException) {
            flash('danger', 'Database connection failed. Please start MySQL and import database/schema.sql.');
            redirect(url('auth', 'login'));
        }

        if (!$user || !password_verify($_POST['password'] ?? '', $user['password'])) {
            flash('danger', 'Invalid credentials.');
            redirect(url('auth', 'login'));
        }
        if (!(int) $user['is_active']) {
            flash('danger', 'Account suspended. Contact admin.');
            redirect(url('auth', 'login'));
        }
        Auth::login($user);
        redirect(url());
    }

    public function logout(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect(url());
        }
        require_post_csrf();
        Auth::logout();
    }
}
