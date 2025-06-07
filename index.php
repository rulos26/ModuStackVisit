<?php
require_once 'config/config.php';

// Autoload básico
spl_autoload_register(function ($class) {
    $paths = [
        CONTROLLERS_PATH,
        MODELS_PATH
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Router básico
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Controlador por defecto
$controllerName = !empty($url[0]) ? ucfirst($url[0]) . 'Controller' : 'HomeController';
$actionName = !empty($url[1]) ? $url[1] : 'index';

// Cargar el controlador
if (file_exists(CONTROLLERS_PATH . $controllerName . '.php')) {
    $controller = new $controllerName();
    if (method_exists($controller, $actionName)) {
        $controller->$actionName();
    } else {
        // Error 404 - Acción no encontrada
        header("HTTP/1.0 404 Not Found");
        echo "Acción no encontrada";
    }
} else {
    // Error 404 - Controlador no encontrado
    header("HTTP/1.0 404 Not Found");
    echo "Controlador no encontrado";
} 