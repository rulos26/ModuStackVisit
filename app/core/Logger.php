<?php
class Logger {
    private static $instance = null;
    private $logPath;
    private $environment;

    private function __construct() {
        $this->logPath = BASE_PATH . '/logs/';
        $this->environment = getenv('APP_ENV') ?: 'development';
        
        // Crear directorio de logs si no existe
        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function log($message, $type = 'php', $level = 'ERROR') {
        $logFile = $this->logPath . $type . '_errors.log';
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[{$timestamp}] [{$level}] {$message}" . PHP_EOL;
        
        error_log($logMessage, 3, $logFile);
    }

    public function logPhpError($errno, $errstr, $errfile, $errline) {
        $message = "Error {$errno}: {$errstr} in {$errfile} on line {$errline}";
        $this->log($message, 'php');
        
        if ($this->environment === 'development') {
            echo "<div style='color: red; padding: 10px; margin: 10px; border: 1px solid red;'>";
            echo "<strong>Error PHP:</strong> {$message}";
            echo "</div>";
        }
    }

    public function logDatabaseError($message, $sql = '') {
        if ($sql) {
            $message .= " SQL: {$sql}";
        }
        $this->log($message, 'db');
    }

    public function logServerError($message) {
        $this->log($message, 'server');
    }

    public function isDevelopment() {
        return $this->environment === 'development';
    }
} 