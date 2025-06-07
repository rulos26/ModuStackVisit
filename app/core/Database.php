<?php
class Database {
    private static $instance = null;
    private $pdo;
    private $logger;

    private function __construct() {
        $this->logger = Logger::getInstance();
        
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            $this->logger->logDatabaseError("Error de conexión: " . $e->getMessage());
            throw new Exception("Error de conexión a la base de datos");
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            $this->logger->logDatabaseError($e->getMessage(), $sql);
            throw new Exception("Error en la consulta a la base de datos");
        }
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function insert($table, $data) {
        $fields = array_keys($data);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $fields) . ") 
                VALUES (" . implode(', ', $placeholders) . ")";
        
        try {
            $this->query($sql, array_values($data));
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->logger->logDatabaseError($e->getMessage(), $sql);
            throw new Exception("Error al insertar en la base de datos");
        }
    }

    public function update($table, $data, $where, $whereParams = []) {
        $fields = array_map(function($field) {
            return "{$field} = ?";
        }, array_keys($data));
        
        $sql = "UPDATE {$table} SET " . implode(', ', $fields) . " WHERE {$where}";
        
        try {
            $params = array_merge(array_values($data), $whereParams);
            return $this->query($sql, $params)->rowCount();
        } catch (PDOException $e) {
            $this->logger->logDatabaseError($e->getMessage(), $sql);
            throw new Exception("Error al actualizar en la base de datos");
        }
    }

    public function delete($table, $where, $params = []) {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        
        try {
            return $this->query($sql, $params)->rowCount();
        } catch (PDOException $e) {
            $this->logger->logDatabaseError($e->getMessage(), $sql);
            throw new Exception("Error al eliminar en la base de datos");
        }
    }
} 