<?php
namespace App\Controllers\Front;

use App\Core\Auth;
use App\Models\User;
use App\Services\Ls25ServerService;

class ApiController
{
    public function ls25Status(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if (!Auth::check()) {
            http_response_code(403);
            echo json_encode(['error' => 'unauthorized']);
            return;
        }

        $user = Auth::user();
        if (!$user) {
            http_response_code(403);
            echo json_encode(['error' => 'unauthorized']);
            return;
        }

        $userModel = new User();
        if (!$userModel->hasPermission((int)$user['id'], 'view_ls25_server')) {
            http_response_code(403);
            echo json_encode(['error' => 'forbidden']);
            return;
        }

        $service = new Ls25ServerService();
        echo json_encode($service->getStatus());
    }
}
