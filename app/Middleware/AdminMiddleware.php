<?php
namespace App\Middleware;

use App\Core\Auth;

class AdminMiddleware
{
    public static function handle(): void
    {
        if (!Auth::check()) {
            redirect('/login');
        }
        if (!Auth::hasPermission('dashboard.view')) {
            http_response_code(403);
            exit('403 - Zugriff verweigert');
        }
    }
}
