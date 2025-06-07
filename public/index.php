<?php
// Cargar variables de entorno desde .env si existe
if (file_exists(dirname(__DIR__) . '/.env')) {
    $lines = file(dirname(__DIR__) . '/.env');
    foreach ($lines as $line) {
        if (trim($line) && strpos($line, '=') !== false) {
            putenv(trim($line));
        }
    }
}

// Definir rutas base
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CONTROLLERS_PATH', APP_PATH . '/controllers/');
define('MODELS_PATH', APP_PATH . '/models/');
define('VIEWS_PATH', APP_PATH . '/views/');
define('PUBLIC_PATH', BASE_PATH . '/public/');

// Configuración de logs
require_once APP_PATH . '/core/Logger.php';
$logger = Logger::getInstance();
$env = getenv('APP_ENV') ?: 'development';

if ($env === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
}
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs/php_errors.log');
error_reporting(E_ALL);

// Registrar errores de PHP
set_error_handler(function($errno, $errstr, $errfile, $errline) use ($logger) {
    $logger->logPhpError($errno, $errstr, $errfile, $errline);
    return false;
});

// Registrar excepciones no capturadas
set_exception_handler(function($exception) use ($logger) {
    $logger->logPhpError($exception->getCode(), $exception->getMessage(), $exception->getFile(), $exception->getLine());
    http_response_code(500);
    if ($logger->isDevelopment()) {
        echo '<pre>' . $exception . '</pre>';
    } else {
        echo 'Error interno del servidor.';
    }
});

// Registrar errores fatales
register_shutdown_function(function() use ($logger) {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        $logger->logPhpError($error['type'], $error['message'], $error['file'], $error['line']);
    }
});

// Autoload básico
spl_autoload_register(function ($class) {
    $paths = [
        CONTROLLERS_PATH,
        MODELS_PATH,
        APP_PATH . '/core/'
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
        $logger->logServerError("Acción no encontrada: $controllerName::$actionName");
        header("HTTP/1.0 404 Not Found");
        echo "Acción no encontrada";
    }
} else {
    $logger->logServerError("Controlador no encontrado: $controllerName");
    header("HTTP/1.0 404 Not Found");
    echo "Controlador no encontrado";
}