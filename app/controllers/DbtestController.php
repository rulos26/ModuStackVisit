<?php
class DbtestController extends BaseController
{
    public function index()
    {
        $status = '';
        $error = '';
        try {
            $pdo = Database::getConnection();
            if ($pdo) {
                $status = 'Conexión exitosa a la base de datos.';
            } else {
                $status = 'No se pudo conectar a la base de datos.';
            }
        } catch (Exception $e) {
            $status = 'Error de conexión a la base de datos.';
            $error = $e->getMessage();
        }
        $this->render('dbtest', [
            'title' => 'Prueba de conexión a la base de datos',
            'status' => $status,
            'error' => $error
        ]);
    }
} 