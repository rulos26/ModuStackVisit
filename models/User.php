<?php
class User {
    private $db;

    public function __construct() {
        // Simulación de conexión a base de datos
        $this->db = null;
    }

    public function getCurrentUser() {
        // Simulación de datos de usuario
        return [
            'id' => 1,
            'name' => 'Usuario Demo',
            'email' => 'demo@modustack.com'
        ];
    }

    public function getAllUsers() {
        // Simulación de lista de usuarios
        return [
            [
                'id' => 1,
                'name' => 'Usuario Demo 1',
                'email' => 'demo1@modustack.com'
            ],
            [
                'id' => 2,
                'name' => 'Usuario Demo 2',
                'email' => 'demo2@modustack.com'
            ]
        ];
    }
} 