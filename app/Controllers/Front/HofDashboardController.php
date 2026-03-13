<?php
namespace App\Controllers\Front;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Farm;
use App\Models\Field;

class HofDashboardController extends Controller
{
    protected function requireLogin(): array
    {
        if (!Auth::check()) {
            header('Location: /login');
            exit;
        }

        $user = Auth::user();
        if (!$user) {
            header('Location: /login');
            exit;
        }

        return $user;
    }

    public function index(): void
    {
        $user = $this->requireLogin();

        $farmModel = new Farm();
        $fieldModel = new Field();

        $farm = $farmModel->findByUserId((int)$user['id']);
        $fields = [];
        $members = [];
        $stats = [
            'total_fields' => 0,
            'fertilization_stage_1_done' => 0,
            'fertilization_stage_2_done' => 0,
            'plowed_done' => 0,
            'lime_done' => 0,
            'rolled_done' => 0,
            'total_members' => 0,
        ];

        if ($farm) {
            $fields = $fieldModel->all(['farm_id' => $farm['id']]);
            $members = $farmModel->getMembers((int)$farm['id']);
            $stats = $farmModel->getStats((int)$farm['id']);
        }

        $this->view('front/hof_dashboard', [
            'title' => 'Hof Dashboard',
            'farm' => $farm,
            'fields' => $fields,
            'members' => $members,
            'stats' => $stats,
            'user' => $user
        ], 'frontend');
    }
}
