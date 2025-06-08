<?php
require_once 'BaseController.php';

class HomeController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        $user = $this->userModel->getCurrentUser();
        $data = [
            'title' => 'Inicio - ' . APP_NAME,
            'user' => $user,
            'users' => $this->userModel->getAllUsers(),
            'currentTime' => date('d/m/Y H:i:s'),
            'mail_result' => $_GET['mail_result'] ?? ''
        ];
        
        $this->render('home', $data);
    }

    public function about() {
        $data = [
            'title' => 'Acerca de - ' . APP_NAME,
            'currentTime' => date('d/m/Y H:i:s')
        ];
        
        $this->render('about', $data);
    }

    public function sendTestMail() {
        require_once BASE_PATH . '/vendor/autoload.php';
        $mailConfig = require APP_PATH . '/config/mail.php';
        $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
        $result = '';
        try {
            $mail->isSMTP();
            $mail->Host = $mailConfig['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig['username'];
            $mail->Password = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['secure'];
            $mail->Port = $mailConfig['port'];
            $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
            $mail->addAddress('rulos26@gmail.com', 'Destinatario de Prueba'); // Ahora llega a tu correo real
            $mail->isHTML(true);
            $mail->Subject = 'Correo de prueba desde ModuStack Visit';
            $mail->Body = '<h2>¡Correo de prueba enviado correctamente!</h2><p>Este es un correo de prueba usando PHPMailer.</p>';
            $mail->send();
            $result = 'Correo de prueba enviado correctamente.';
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            $result = 'Error al enviar el correo: ' . $mail->ErrorInfo;
        }
        header('Location: ' . APP_URL . '/home?mail_result=' . urlencode($result));
        exit;
    }
} 