<?php
namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = [], string $layout = 'frontend'): void
    {
        View::render($view, $data, $layout);
    }

    protected function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function requirePost(): void
    {
        if (!is_post()) {
            http_response_code(405);
            exit('Method Not Allowed');
        }
    }
}
