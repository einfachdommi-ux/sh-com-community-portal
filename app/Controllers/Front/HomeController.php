<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\News;
use App\Models\Changelog;
use App\Models\TeamMember;
use App\Core\Auth;
use App\Models\User;
use App\Services\Ls25ServerService;

class HomeController extends Controller
{
    public function index(): void
    {
        $ls25 = null;
        $ls25Permission = false;
        $news = array_slice((new News())->published(), 0, 3);
        $changelogs = array_slice((new Changelog())->latest(), 0, 5);
        $team = array_slice((new TeamMember())->active(), 0, 4);

        $this->view('front/home', compact('news', 'changelogs', 'team'));
        if (Auth::check()) {
        $user = Auth::user();
        if ($user) {
            $userModel = new User();
            $ls25Permission = $userModel->hasPermission((int)$user['id'], 'view_ls25_server');

            if ($ls25Permission) {
                $ls25 = (new Ls25ServerService())->getStatus();
            }
        }
        }
    }
}
