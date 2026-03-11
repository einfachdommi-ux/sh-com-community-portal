<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\TeamMember;
use App\Models\User;

class TeamController extends Controller
{
    public function index(): void
    {
        $team = (new TeamMember())->active();
        $this->view('front/team', compact('team'));
    }

    public function members(): void
    {
        $members = (new User())->withRole();
        $this->view('front/members', compact('members'));
    }
}
