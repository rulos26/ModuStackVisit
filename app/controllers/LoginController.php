<?php
class LoginController extends BaseController
{
    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            $this->redirect(APP_URL . '/home');
        }
        $this->render('login', ['error' => '', 'email' => '']);
    }

    public function login()
    {
        $error = '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Correo electrónico inválido.';
        } elseif (!$this->isPasswordSecure($password)) {
            $error = 'Contraseña insegura.';
        } else {
            $user = User::findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                session_regenerate_id(true);
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['last_activity'] = time();
                setcookie(session_name(), session_id(), [
                    'httponly' => true,
                    'secure' => isset($_SERVER['HTTPS']),
                    'samesite' => 'Strict'
                ]);
                $this->redirect(APP_URL . '/home');
            } else {
                $error = 'Credenciales incorrectas.';
                $this->logLoginError($email, 'Intento fallido');
            }
        }
        $this->render('login', ['error' => $error, 'email' => $email]);
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        $this->redirect(APP_URL . '/login');
    }

    private function isPasswordSecure($password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%^&*()_+\\-=\\[\\]{};\'\":\\\\|,.<>\\/?]).{8,}$/', $password);
    }

    private function logLoginError($email, $msg)
    {
        $logDir = BASE_PATH . '/logs';
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        $file = $logDir . '/login_errors.log';
        $date = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $log = "[$date] [$ip] Email: $email - $msg" . PHP_EOL;
        file_put_contents($file, $log, FILE_APPEND);
    }
} 