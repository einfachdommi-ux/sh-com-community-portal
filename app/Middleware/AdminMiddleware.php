<?php
namespace App\Middleware;

use App\Core\Auth;

class AdminMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        if (!Auth::hasRole('projektleitung')) {
            http_response_code(403);
            exit('Keine Berechtigung.');
        }
    }
}