<?php
class BaseController {
    protected function render($view, $data = []) {
        extract($data);
        require_once VIEWS_PATH . $view . '.php';
    }

    protected function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
} 