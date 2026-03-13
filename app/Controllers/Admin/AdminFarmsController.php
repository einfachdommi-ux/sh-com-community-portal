<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\Farm;

class AdminFarmsController extends Controller
{
    public function index(): void
    {
        $farms = (new Farm())->all();

        $this->view('admin/farms_index', [
            'title' => 'Farm Verwaltung',
            'farms' => $farms,
        ], 'backend');
    }
}
