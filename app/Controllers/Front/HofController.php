<?php
namespace App\Controllers\Front;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Farm;
use App\Models\FarmInvite;

class HofController extends Controller
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

    protected function requireOwner(array $farm, array $user): void
    {
        if (!(new Farm())->isOwner((int)$farm['id'], (int)$user['id'])) {
            http_response_code(403);
            exit('Keine Berechtigung.');
        }
    }

    public function index(): void
    {
        $user = $this->requireLogin();

        $farmModel = new Farm();
        $farm = $farmModel->findByUserId((int)$user['id']);
        $members = [];
        $invites = [];

        if ($farm) {
            $members = $farmModel->getMembers((int)$farm['id']);
            $invites = (new FarmInvite())->allByFarm((int)$farm['id']);
        }

        $this->view('front/hof', [
            'title' => 'Mein Hof',
            'user' => $user,
            'farm' => $farm,
            'members' => $members,
            'invites' => $invites,
            'isOwner' => $farm ? $farmModel->isOwner((int)$farm['id'], (int)$user['id']) : false,
        ], 'frontend');
    }

    public function join(): void
    {
        $this->requirePost();
        $user = $this->requireLogin();

        $farmId = (int)$this->input('farm_id', 0);
        $password = trim((string)$this->input('farm_password', ''));

        if ($farmId <= 0) {
            $_SESSION['flash_error'] = 'Bitte Hof auswählen.';
            header('Location: /hof');
            exit;
        }

        $farmModel = new Farm();
        $farm = $farmModel->find($farmId);

        if (!$farm) {
            $_SESSION['flash_error'] = 'Hof nicht gefunden.';
            header('Location: /hof');
            exit;
        }

        if (!empty($farm['password_hash']) && !$farmModel->verifyPassword($farmId, $password)) {
            $_SESSION['flash_error'] = 'Falsches Hof-Passwort.';
            header('Location: /hof');
            exit;
        }

        $farmModel->join((int)$user['id'], $farmId);

        $_SESSION['flash_success'] = 'Du bist dem Hof beigetreten.';
        header('Location: /hof');
        exit;
    }

    public function leaveConfirm(): void
    {
        $user = $this->requireLogin();
        $farm = (new Farm())->findByUserId((int)$user['id']);

        $this->view('front/hof_leave_confirm', [
            'title' => 'Hof verlassen',
            'user' => $user,
            'farm' => $farm,
        ], 'frontend');
    }

    public function leave(): void
    {
        $this->requirePost();
        $user = $this->requireLogin();

        (new Farm())->leave((int)$user['id']);

        $_SESSION['flash_success'] = 'Du hast den Hof verlassen.';
        header('Location: /hof');
        exit;
    }

    public function setPassword(): void
    {
        $this->requirePost();
        $user = $this->requireLogin();

        $farmModel = new Farm();
        $farm = $farmModel->findByUserId((int)$user['id']);

        if (!$farm) {
            $_SESSION['flash_error'] = 'Kein Hof gefunden.';
            header('Location: /hof');
            exit;
        }

        $this->requireOwner($farm, $user);

        $password = trim((string)$this->input('password', ''));

        if ($password === '') {
            $_SESSION['flash_error'] = 'Bitte ein Passwort eingeben.';
            header('Location: /hof');
            exit;
        }

        $farmModel->setPassword((int)$farm['id'], $password);

        $_SESSION['flash_success'] = 'Hof-Passwort gespeichert.';
        header('Location: /hof');
        exit;
    }

    public function createInvite(): void
    {
        $this->requirePost();
        $user = $this->requireLogin();

        $farmModel = new Farm();
        $farm = $farmModel->findByUserId((int)$user['id']);

        if (!$farm) {
            $_SESSION['flash_error'] = 'Kein Hof gefunden.';
            header('Location: /hof');
            exit;
        }

        $this->requireOwner($farm, $user);

        $token = (new FarmInvite())->createToken((int)$farm['id'], (int)$user['id']);

        $_SESSION['flash_success'] = 'Einladungslink erstellt.';
        $_SESSION['farm_invite_link'] = '/hof/invite/' . $token;

        header('Location: /hof');
        exit;
    }

    public function invite(string $token): void
    {
        $user = $this->requireLogin();

        $inviteModel = new FarmInvite();
        $invite = $inviteModel->findValidByToken($token);

        if (!$invite) {
            $_SESSION['flash_error'] = 'Einladung ungültig oder abgelaufen.';
            header('Location: /hof');
            exit;
        }

        (new Farm())->join((int)$user['id'], (int)$invite['farm_id']);
        $inviteModel->markUsed((int)$invite['id']);

        $_SESSION['flash_success'] = 'Du bist dem Hof per Einladung beigetreten.';
        header('Location: /hof');
        exit;
    }

    public function removeMember(): void
    {
        $this->requirePost();
        $user = $this->requireLogin();

        $memberId = (int)$this->input('member_id', 0);
        if ($memberId <= 0) {
            $_SESSION['flash_error'] = 'Ungültiges Mitglied.';
            header('Location: /hof');
            exit;
        }

        $farmModel = new Farm();
        $farm = $farmModel->findByUserId((int)$user['id']);

        if (!$farm) {
            $_SESSION['flash_error'] = 'Kein Hof gefunden.';
            header('Location: /hof');
            exit;
        }

        $this->requireOwner($farm, $user);

        $farmModel->removeMember((int)$farm['id'], $memberId);

        $_SESSION['flash_success'] = 'Mitglied wurde entfernt.';
        header('Location: /hof');
        exit;
    }
}
