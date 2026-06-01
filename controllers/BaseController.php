<?php
declare(strict_types=1);

abstract class BaseController
{
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}

