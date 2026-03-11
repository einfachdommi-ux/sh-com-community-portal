<?php
namespace App\Controllers\Front;

use App\Core\Auth;
use App\Core\Controller;
use App\Core\Logger;
use App\Core\Mailer;
use App\Models\Role;
use App\Models\User;

class AuthController extends Controller
{
    public function login(): void
    {
        if (is_post()) {
            if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
                flash('error', 'Ungültiges CSRF-Token.');
                redirect('/login');
            }

            $email = trim((string)$this->input('email'));
            $password = (string)$this->input('password');

            if (Auth::attempt($email, $password)) {
                Logger::audit('auth', 'login');
                redirect('/admin');
            }

            flash('error', 'Login fehlgeschlagen oder Account nicht verifiziert.');
            redirect('/login');
        }

        $this->view('front/login');
    }

    public function register(): void
    {
        if (is_post()) {
            if (!hash_equals($_SESSION['_csrf'] ?? '', $_POST['_csrf'] ?? '')) {
                flash('error', 'Ungültiges CSRF-Token.');
                redirect('/register');
            }

            $_SESSION['_old'] = $_POST;
            $username = trim((string)$this->input('username'));
            $email = trim((string)$this->input('email'));
            $password = (string)$this->input('password');
            $passwordConfirm = (string)$this->input('password_confirmation');

            if ($username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8 || $password !== $passwordConfirm) {
                flash('error', 'Bitte prüfe deine Eingaben.');
                redirect('/register');
            }

            $userModel = new User();
            if ($userModel->findByEmail($email)) {
                flash('error', 'Diese E-Mail ist bereits registriert.');
                redirect('/register');
            }

            $token = bin2hex(random_bytes(32));
            $userId = $userModel->create([
                'username' => $username,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'email_verification_token' => $token,
                'is_verified' => 0,
                'is_active' => 1,
            ]);

            $communityRole = \App\Core\Database::query('SELECT id FROM roles WHERE slug = "community-member" LIMIT 1')->fetch();
            if ($communityRole) {
                $userModel->assignRole($userId, (int)$communityRole['id']);
            }

            $verifyUrl = base_url('/verify-email/' . $token);
            ob_start();
            $url = $verifyUrl;
            require APP_PATH . '/Views/emails/verify.php';
            $html = ob_get_clean();
            $mailer = new Mailer();
            try {
                $mailer->send($email, 'Bitte bestätige deine Registrierung bei SH-COM', $html, $userId, 'verification');
            } catch (\Throwable $e) {
                Logger::mail($userId, 'verification', $email, 'Bitte bestätige deine Registrierung bei SH-COM', 'failed', null, $e->getMessage());
            }

            unset($_SESSION['_old']);
            flash('success', 'Registrierung erfolgreich. Bitte bestätige deine E-Mail-Adresse.');
            redirect('/login');
        }

        $this->view('front/register');
    }

    public function verifyEmail(string $token): void
    {
        $ok = (new User())->verifyByToken($token);
        flash($ok ? 'success' : 'error', $ok ? 'Dein Account wurde verifiziert.' : 'Der Verifizierungslink ist ungültig.');
        redirect('/login');
    }

    public function logout(): void
    {
        Auth::logout();
        flash('success', 'Du wurdest abgemeldet.');
        redirect('/');
    }
}
