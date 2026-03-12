<?php
namespace App\Controllers\Front;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(): void
    {
        if (!Auth::check()) {
            flash('error', 'Bitte logge dich ein.');
            redirect('/login');
        }

        $user = (new User())->find((int) Auth::id());

        if (!$user) {
            Auth::logout();
            flash('error', 'Benutzer nicht gefunden.');
            redirect('/login');
        }

        $this->view('front/profile', compact('user'));
    }

    public function update(): void
    {
        $this->requirePost();

        if (!Auth::check()) {
            flash('error', 'Bitte logge dich ein.');
            redirect('/login');
        }

        if (!$this->checkCsrf()) {
            flash('error', 'Ungültiges CSRF-Token.');
            redirect('/profile');
        }

        $userModel = new User();
        $userId = (int) Auth::id();
        $current = $userModel->find($userId);

        if (!$current) {
            Auth::logout();
            flash('error', 'Benutzer nicht gefunden.');
            redirect('/login');
        }

        $username = trim((string) $this->input('username'));
        $email = trim((string) $this->input('email'));
        $firstName = trim((string) $this->input('first_name'));
        $lastName = trim((string) $this->input('last_name'));
        $bio = trim((string) $this->input('bio'));
        $discord = trim((string) $this->input('discord'));

        if ($username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Bitte prüfe Benutzername und E-Mail.');
            redirect('/profile');
        }

        $existing = $userModel->findByEmail($email);
        if ($existing && (int) $existing['id'] !== $userId) {
            flash('error', 'Diese E-Mail-Adresse wird bereits verwendet.');
            redirect('/profile');
        }

        $existingUsername = $userModel->findByUsername($username);
        if ($existingUsername && (int) $existingUsername['id'] !== $userId) {
            flash('error', 'Dieser Benutzername ist bereits vergeben.');
            redirect('/profile');
        }

        $userModel->updateProfile($userId, [
            'username' => $username,
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'bio' => $bio,
            'discord' => $discord,
        ]);

        flash('success', 'Profil erfolgreich aktualisiert.');
        redirect('/profile');
    }

    public function password(): void
    {
        $this->requirePost();

        if (!Auth::check()) {
            flash('error', 'Bitte logge dich ein.');
            redirect('/login');
        }

        if (!$this->checkCsrf()) {
            flash('error', 'Ungültiges CSRF-Token.');
            redirect('/profile');
        }

        $userModel = new User();
        $userId = (int) Auth::id();
        $user = $userModel->find($userId);

        if (!$user) {
            Auth::logout();
            flash('error', 'Benutzer nicht gefunden.');
            redirect('/login');
        }

        $currentPassword = (string) $this->input('current_password');
        $newPassword = (string) $this->input('new_password');
        $confirmPassword = (string) $this->input('confirm_password');

        if (!password_verify($currentPassword, (string) $user['password_hash'])) {
            flash('error', 'Das aktuelle Passwort ist falsch.');
            redirect('/profile');
        }

        if (strlen($newPassword) < 8) {
            flash('error', 'Das neue Passwort muss mindestens 8 Zeichen lang sein.');
            redirect('/profile');
        }

        if ($newPassword !== $confirmPassword) {
            flash('error', 'Die neuen Passwörter stimmen nicht überein.');
            redirect('/profile');
        }

        $userModel->updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));

        flash('success', 'Passwort erfolgreich geändert.');
        redirect('/profile');
    }

    private function checkCsrf(): bool
    {
        $sessionToken = $_SESSION['_csrf'] ?? null;
        $postToken = $_POST['_csrf'] ?? null;

        if (!$sessionToken) {
            return true;
        }

        return is_string($postToken) && hash_equals($sessionToken, $postToken);
    }
}
