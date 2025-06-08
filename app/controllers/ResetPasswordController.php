<?php
class ResetPasswordController extends BaseController
{
    public function index()
    {
        $token = $_GET['token'] ?? '';
        $error = '';
        $success = '';
        if (!$token) {
            $error = 'Token inválido.';
            $this->render('reset-password', ['error' => $error, 'success' => '', 'token' => $token]);
            return;
        }
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND expiracion > NOW()");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();
        if (!$reset) {
            $error = 'El enlace ha expirado o es inválido.';
            $this->render('reset-password', ['error' => $error, 'success' => '', 'token' => $token]);
            return;
        }
        $this->render('reset-password', ['error' => '', 'success' => '', 'token' => $token]);
    }

    public function update()
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $error = '';
        $success = '';
        if (!$token) {
            $error = 'Token inválido.';
        } elseif ($password !== $password2) {
            $error = 'Las contraseñas no coinciden.';
        } elseif (!$this->isPasswordSecure($password)) {
            $error = 'La contraseña no cumple los requisitos de seguridad.';
        } else {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND expiracion > NOW()");
            $stmt->execute([$token]);
            $reset = $stmt->fetch();
            if ($reset) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt2 = $db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                $stmt2->execute([$hash, $reset['user_id']]);
                $db->prepare("DELETE FROM password_resets WHERE id = ?")->execute([$reset['id']]);
                $success = 'Contraseña restablecida correctamente. Ahora puedes iniciar sesión.';
                $this->logAuthError($reset['user_id'], 'Password reset', 'Contraseña cambiada con éxito.');
                $this->render('reset-password', ['error' => '', 'success' => $success, 'token' => '']);
                return;
            } else {
                $error = 'El enlace ha expirado o es inválido.';
            }
        }
        $this->logAuthError('', 'Password reset fallido', $error);
        $this->render('reset-password', ['error' => $error, 'success' => '', 'token' => $token]);
    }

    private function isPasswordSecure($password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%^&*()_+\\-=\\[\\]{};\'\":\\\\|,.<>\\/?]).{8,}$/', $password);
    }

    private function logAuthError($usuario, $accion, $descripcion)
    {
        $logDir = BASE_PATH . '/logs';
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        $file = $logDir . '/auth_errors.log';
        $date = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $log = "[$date] [$ip] Acción: $accion | Usuario: $usuario | $descripcion" . PHP_EOL;
        file_put_contents($file, $log, FILE_APPEND);
    }
} 