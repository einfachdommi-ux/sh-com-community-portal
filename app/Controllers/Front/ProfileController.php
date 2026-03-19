<?php
namespace App\Controllers\Front;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\User;

class ProfileController extends Controller
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
        $this->view('front/profile', ['user' => $user], 'frontend');
    }

    public function update(): void
    {
        $this->requirePost();
        $user = $this->requireLogin();

        if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Ungültiges CSRF-Token.';
            header('Location: /profile');
            exit;
        }

        $avatarPath = $user['avatar'] ?? null;

        if (isset($_FILES['avatar_file']) && is_array($_FILES['avatar_file']) && ($_FILES['avatar_file']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
            if (($_FILES['avatar_file']['error'] ?? UPLOAD_ERR_OK) === UPLOAD_ERR_OK) {
                $tmpPath = $_FILES['avatar_file']['tmp_name'] ?? '';
                $originalName = $_FILES['avatar_file']['name'] ?? '';
                $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                $allowed = ['jpg', 'jpeg', 'png', 'webp'];

                if (in_array($ext, $allowed, true)) {
                    $uploadDir = BASE_PATH . '/public/assets/uploads/avatars';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    $fileName = 'avatar_' . (int)$user['id'] . '_' . time() . '.' . $ext;
                    $targetPath = $uploadDir . '/' . $fileName;

                    if (move_uploaded_file($tmpPath, $targetPath)) {
                        $avatarPath = '/assets/uploads/avatars/' . $fileName;
                    }
                }
            }
        }

        $data = [
            'first_name' => trim((string)$this->input('first_name', '')),
            'last_name' => trim((string)$this->input('last_name', '')),
            'username' => trim((string)$this->input('username', '')),
            'email' => trim((string)$this->input('email', '')),
            'bio' => trim((string)$this->input('bio', '')),
            'discord' => trim((string)$this->input('discord', '')),
            'website' => trim((string)$this->input('website', '')),
            'location' => trim((string)$this->input('location', '')),
            'instagram' => trim((string)$this->input('instagram', '')),
            'facebook' => trim((string)$this->input('facebook', '')),
            'snapchat' => trim((string)$this->input('snapchat', '')),
            'x_profile' => trim((string)$this->input('x_profile', '')),
            'epic_games' => trim((string)$this->input('epic_games', '')),
            'steam' => trim((string)$this->input('steam', '')),
            'ea_app' => trim((string)$this->input('ea_app', '')),
            'twitch' => trim((string)$this->input('twitch', '')),
            'youtube' => trim((string)$this->input('youtube', '')),
            'avatar' => $avatarPath,
            'is_public_profile' => (int)$this->input('is_public_profile', 0),
            'show_email_public' => (int)$this->input('show_email_public', 0),
        ];

        (new User())->updateProfile((int)$user['id'], $data);

        $_SESSION['flash_success'] = 'Profil erfolgreich aktualisiert.';
        header('Location: /profile');
        exit;
    }

    public function security(): void
    {
        $this->requirePost();
        $user = $this->requireLogin();

        if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Ungültiges CSRF-Token.';
            header('Location: /profile');
            exit;
        }

        $currentPassword = (string)$this->input('current_password', '');
        $newPassword = (string)$this->input('new_password', '');
        $confirmPassword = (string)$this->input('confirm_password', '');

        if ($newPassword === '' || $newPassword !== $confirmPassword) {
            $_SESSION['flash_error'] = 'Die neuen Passwörter stimmen nicht überein.';
            header('Location: /profile');
            exit;
        }

        $freshUser = (new User())->find((int)$user['id']);
        if (!$freshUser || !password_verify($currentPassword, $freshUser['password_hash'])) {
            $_SESSION['flash_error'] = 'Das aktuelle Passwort ist falsch.';
            header('Location: /profile');
            exit;
        }

        (new User())->updatePassword((int)$user['id'], password_hash($newPassword, PASSWORD_DEFAULT));

        $_SESSION['flash_success'] = 'Passwort erfolgreich geändert.';
        header('Location: /profile');
        exit;
    }

    public function public(string $username): void
    {
        $profileUser = (new User())->findPublicProfileByUsername($username);

        if (!$profileUser) {
            http_response_code(404);
            echo 'Profil nicht gefunden.';
            return;
        }

        $this->view('front/profile_public', ['profileUser' => $profileUser], 'frontend');
    }
}
