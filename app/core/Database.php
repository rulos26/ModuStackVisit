<?php
class Database {
    private static ?PDO $connection = null;
    private static string $host = 'localhost';
    private static string $dbname = 'u494150416_ModuStackVisit';
    private static string $user = 'u494150416_root';
    private static string $pass = '0382646740Ju*';
    private static string $charset = 'utf8mb4';
    private static string $logDir = __DIR__ . '/../../logs';
    private static string $logFile = '/db_errors.log';

    /**
     * Devuelve una instancia PDO lista para usar.
     */
    public static function getConnection(): ?PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=" . self::$charset;
                self::$connection = new PDO($dsn, self::$user, self::$pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                self::logError("Error de conexión: " . $e->getMessage());
                $env = getenv('APP_ENV') ?: 'production';
                if ($env === 'development') {
                    echo "<b>Error de conexión a la base de datos:</b> " . $e->getMessage();
                } else {
                    echo "Error de conexión a la base de datos.";
                }
                return null;
            }
        }
        return self::$connection;
    }

    /**
     * Ejecuta una consulta y maneja errores.
     */
    public static function query(string $sql, array $params = []): ?PDOStatement
    {
        try {
            $pdo = self::getConnection();
            if ($pdo === null) return null;
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            self::logError("Error en consulta: " . $e->getMessage() . " | SQL: $sql");
            $env = getenv('APP_ENV') ?: 'production';
            if ($env === 'development') {
                echo "<b>Error en consulta:</b> " . $e->getMessage();
            } else {
                echo "Error en la consulta a la base de datos.";
            }
            return null;
        }
    }

    /**
     * Registra errores en logs/db_errors.log con fecha y hora.
     */
    private static function logError(string $message): void
    {
        // Crear carpeta logs si no existe
        if (!is_dir(self::$logDir)) {
            mkdir(self::$logDir, 0777, true);
        }
        $file = self::$logDir . self::$logFile;
        $date = date('Y-m-d H:i:s');
        $logMessage = "[$date] $message" . PHP_EOL;
        file_put_contents($file, $logMessage, FILE_APPEND);
    }
} 