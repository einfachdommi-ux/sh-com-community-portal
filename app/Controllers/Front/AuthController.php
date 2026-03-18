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
                redirect('/');
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

            $guestRole = \App\Core\Database::query('SELECT id FROM roles WHERE slug = "gast" LIMIT 1')->fetch();
            if ($guestRole) {
                $userModel->assignRole($userId, (int)$guestRole['id']);
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

            $guestRole = \App\Core\Database::query('SELECT id FROM roles WHERE slug = "gast" LIMIT 1')->fetch();
            if ($guestRole) {
            $userModel->assignRole($userId, (int)$guestRole['id']);
            }

            $verifyUrl = base_url('/verify-email/' . $token);
            ob_start();
            $url = $verifyUrl;
            require APP_PATH . '/Views/emails/verify.php';
            $html = ob_get_clean();
            $mailer = new Mailer();
            try {
                $mailer->send($email, 'Bitte bestätige deine Registrierung bei der Schleswig-Holstein Community', $html, $userId, 'verification');
            } catch (\Throwable $e) {
                Logger::mail($userId, 'verification', $email, 'Bitte bestätige deine Registrierung bei der Schleswig-Holstein Community', 'failed', null, $e->getMessage());
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
