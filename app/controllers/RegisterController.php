<?php
class RegisterController extends BaseController
{
    public function index()
    {
        $this->render('register', ['error' => '', 'success' => '', 'old' => []]);
    }

    public function register()
    {
        $error = '';
        $success = '';
        $old = [
            'nombre' => $_POST['nombre'] ?? '',
            'email' => $_POST['email'] ?? ''
        ];
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'CLI';

        // Validaciones
        if (empty($nombre) || empty($email) || empty($password) || empty($password2)) {
            $error = 'Todos los campos son obligatorios.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Correo electrónico inválido.';
        } elseif ($password !== $password2) {
            $error = 'Las contraseñas no coinciden.';
        } elseif (!$this->isPasswordSecure($password)) {
            $error = 'La contraseña no cumple los requisitos de seguridad.';
        } elseif (User::findByEmail($email)) {
            $error = 'El correo ya está registrado.';
        }

        if ($error) {
            $this->logAuthError($email, 'Registro fallido', $error);
            $this->render('register', ['error' => $error, 'success' => '', 'old' => $old]);
            return;
        }

        // Crear usuario
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $db = Database::getConnection();
        $db->beginTransaction();
        try {
            $stmt = $db->prepare("INSERT INTO usuarios (nombre, email, password, creado_en) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$nombre, $email, $hash]);
            $userId = $db->lastInsertId();
            // Asignar rol evaluador
            $stmt2 = $db->prepare("INSERT INTO usuario_rol (usuario_id, rol_id) VALUES (?, (SELECT id FROM roles WHERE nombre = 'evaluador' LIMIT 1))");
            $stmt2->execute([$userId]);
            $db->commit();
            $this->logAuthError($email, 'Registro exitoso', 'Usuario registrado correctamente. IP: ' . $ip);
            $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
            $this->render('register', ['error' => '', 'success' => $success, 'old' => []]);
        } catch (Exception $e) {
            $db->rollBack();
            $this->logAuthError($email, 'Registro fallido', $e->getMessage());
            $this->render('register', ['error' => 'Error al registrar usuario.', 'success' => '', 'old' => $old]);
        }
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