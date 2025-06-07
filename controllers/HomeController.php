<?php
require_once 'BaseController.php';

class HomeController extends BaseController {
    public function index() {
        $userModel = new User();
        $user = $userModel->getCurrentUser();
        
        $data = [
            'title' => 'Inicio - ModuStack Visit',
            'user' => $user,
            'currentTime' => date('d/m/Y H:i:s')
        ];
        
        $this->render('home', $data);
    }
} 