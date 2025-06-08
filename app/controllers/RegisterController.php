<?php
class RegisterController extends BaseController
{
    public function index()
    {
        $this->render('register', ['error' => '', 'success' => '', 'old' => []]);
    }

    public function register()
    {
        // Prueba de depuración: mostrar datos recibidos y verificar conexión
        echo '<pre>POST:';
        var_dump($_POST);
        echo '</pre>';
        
        try {
            $db = Database::getConnection();
            echo '<pre>DB Conexión:';
            var_dump($db);
            echo '</pre>';
        } catch (Exception $e) {
            echo '<pre>Error de conexión: ' . $e->getMessage() . '</pre>';
        }
        exit;
    }

    private function isPasswordSecure($password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[!@#$%^&*()_+\\-=\\[\\]{};\'\":\\\\|,.<>\\/?]).{8,}$/', $password);
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