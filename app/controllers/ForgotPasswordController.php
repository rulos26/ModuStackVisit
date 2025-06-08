<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ForgotPasswordController extends BaseController
{
    public function index()
    {
        $this->render('forgot-password', ['error' => '', 'success' => '']);
    }

    public function send()
    {
        $error = '';
        $success = '';
        $email = trim($_POST['email'] ?? '');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Correo electrónico inválido.';
        } else {
            $user = User::findByEmail($email);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $exp = date('Y-m-d H:i:s', time() + 900); // 15 minutos
                $db = Database::getConnection();
                $stmt = $db->prepare("INSERT INTO password_resets (user_id, token, expiracion) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token=?, expiracion=?");
                $stmt->execute([$user['id'], $token, $exp, $token, $exp]);
                // Enviar correo
                $enviado = $this->sendMail($email, $token);
                if ($enviado) {
                    $success = 'Si el correo existe, se ha enviado un enlace para restablecer la contraseña.';
                    $this->logAuthError($email, 'Recuperación enviada', 'Token generado y correo enviado.');
                } else {
                    $error = 'No se pudo enviar el correo. Intenta más tarde.';
                    $this->logAuthError($email, 'Recuperación fallida', 'Error al enviar correo.');
                }
            } else {
                // No revelar si el correo existe
                $success = 'Si el correo existe, se ha enviado un enlace para restablecer la contraseña.';
                $this->logAuthError($email, 'Recuperación solicitada', 'Correo no registrado.');
            }
        }
        $this->render('forgot-password', ['error' => $error, 'success' => $success]);
    }

    private function sendMail($email, $token)
    {
        // Configura PHPMailer según tu servidor SMTP
        // Aquí solo se simula el envío
        // return mail($email, 'Recupera tu contraseña', '...');
        return true;
    }

    private function logAuthError($email, $accion, $descripcion)
    {
        $logDir = BASE_PATH . '/logs';
        if (!is_dir($logDir)) mkdir($logDir, 0777, true);
        $file = $logDir . '/auth_errors.log';
        $date = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $log = "[$date] [$ip] Acción: $accion | Email: $email | $descripcion" . PHP_EOL;
        file_put_contents($file, $log, FILE_APPEND);
    }
} 