<?php
namespace App\Controllers\Front;

use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index(): void
    {
        if (empty($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->find((int)$_SESSION['user']['id']);

        if (!$user) {
            http_response_code(404);
            exit('Benutzer nicht gefunden.');
        }

        $this->view('front/profile', [
            'user' => $user,
        ], 'frontend');
    }

    public function public(string $username): void
    {
        $userModel = new User();
        $user = $userModel->findPublicByUsername($username);

        if (!$user) {
            http_response_code(404);
            exit('Profil nicht gefunden.');
        }

        $this->view('front/public-profile', [
            'user' => $user,
        ], 'frontend');
    }

    public function update(): void
    {
        $this->requirePost();

        if (empty($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['user']['id'];
        $userModel = new User();
        $currentUser = $userModel->find($userId);

        if (!$currentUser) {
            http_response_code(404);
            exit('Benutzer nicht gefunden.');
        }

        $avatarPath = $currentUser['avatar'] ?? '';
        $uploadedAvatar = $this->handleAvatarUpload();
        if ($uploadedAvatar !== '') {
            $avatarPath = $uploadedAvatar;
        }

        $isPublicProfile = $this->input('is_public_profile', 0) ? 1 : 0;
        $showEmail = $this->input('show_email_public', 0) ? 1 : 0;

        $userModel->updateProfile($userId, [
            'first_name' => trim((string)$this->input('first_name', '')),
            'last_name' => trim((string)$this->input('last_name', '')),
            'username' => trim((string)$this->input('username', '')),
            'email' => trim((string)$this->input('email', '')),
            'bio' => trim((string)$this->input('bio', '')),
            'discord' => trim((string)$this->input('discord', '')),
            'avatar' => $avatarPath,
            'website' => trim((string)$this->input('website', '')),
            'location' => trim((string)$this->input('location', '')),
            'is_public_profile' => $isPublicProfile,
            'show_email_public' => $showEmail,
        ]);

        $_SESSION['flash_success'] = 'Profil erfolgreich aktualisiert.';
        header('Location: /profile');
        exit;
    }

    public function security(): void
    {
        $this->requirePost();

        if (empty($_SESSION['user']['id'])) {
            header('Location: /login');
            exit;
        }

        $userId = (int)$_SESSION['user']['id'];
        $currentPassword = (string)$this->input('current_password', '');
        $newPassword = (string)$this->input('new_password', '');
        $confirmPassword = (string)$this->input('confirm_password', '');

        if ($newPassword === '' || strlen($newPassword) < 8) {
            $_SESSION['flash_error'] = 'Das neue Passwort muss mindestens 8 Zeichen lang sein.';
            header('Location: /profile');
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['flash_error'] = 'Die neuen Passwörter stimmen nicht überein.';
            header('Location: /profile');
            exit;
        }

        $userModel = new User();
        $user = $userModel->find($userId);

        if (!$user || !password_verify($currentPassword, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Das aktuelle Passwort ist falsch.';
            header('Location: /profile');
            exit;
        }

        $userModel->updatePassword($userId, password_hash($newPassword, PASSWORD_DEFAULT));

        $_SESSION['flash_success'] = 'Sicherheitseinstellungen gespeichert. Das Passwort wurde aktualisiert.';
        header('Location: /profile');
        exit;
    }

    private function handleAvatarUpload(): string
    {
        if (!isset($_FILES['avatar_file']) || !is_array($_FILES['avatar_file'])) {
            return '';
        }

        $file = $_FILES['avatar_file'];
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            return '';
        }

        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            return '';
        }

        $tmpPath = $file['tmp_name'] ?? '';
        $originalName = $file['name'] ?? '';
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed, true)) {
            return '';
        }

        $uploadDir = BASE_PATH . '/public/assets/uploads/avatars';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $fileName = 'avatar_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = $uploadDir . '/' . $fileName;

        if (!move_uploaded_file($tmpPath, $target)) {
            return '';
        }

        return '/assets/uploads/avatars/' . $fileName;
    }
}
