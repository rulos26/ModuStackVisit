<?php
class BaseController {
    protected function render($view, $data = []) {
        // Extraer variables para la vista
        extract($data);
        
        // Incluir el layout principal
        $content = VIEWS_PATH . $view . '.php';
        require_once VIEWS_PATH . 'layouts/main.php';
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
} 