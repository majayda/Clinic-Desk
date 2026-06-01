<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseController.php';

final class ErrorController extends BaseController
{
    public function forbidden(): void
    {
        http_response_code(403);
        $this->view('errors/403', ['pageTitle' => 'Forbidden']);
    }

    public function notFound(): void
    {
        http_response_code(404);
        $this->view('errors/404', ['pageTitle' => 'Not Found']);
    }

    public function __call(string $name, array $args): void
    {
        $name === '403' ? $this->forbidden() : $this->notFound();
    }
}

