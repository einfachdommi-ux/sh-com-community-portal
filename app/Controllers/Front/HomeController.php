<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\News;
use App\Models\Changelog;
use App\Models\TeamMember;

class HomeController extends Controller
{
    public function index(): void
    {
        $news = array_slice((new News())->published(), 0, 3);
        $changelogs = array_slice((new Changelog())->latest(), 0, 5);
        $team = array_slice((new TeamMember())->active(), 0, 4);

        $this->view('front/home', compact('news', 'changelogs', 'team'));
    }
}
