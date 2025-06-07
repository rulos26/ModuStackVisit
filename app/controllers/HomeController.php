<?php
require_once 'BaseController.php';

class HomeController extends BaseController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function index() {
        $data = [
            'title' => 'Inicio - ' . APP_NAME,
            'user' => $this->userModel->getCurrentUser(),
            'users' => $this->userModel->getAllUsers(),
            'currentTime' => date('d/m/Y H:i:s')
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
} 