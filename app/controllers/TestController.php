<?php
class TestController extends BaseController
{
    public function index()
    {
        // Ejemplo de error PHP (descomenta para probar el log de PHP)
        // echo $variableNoDefinida;

        // Ejemplo de error de base de datos (descomenta para probar el log de DB)
        // $db = Database::getInstance();
        // $db->query("SELECT * FROM tabla_que_no_existe");

        // Ejemplo de error de servidor (descomenta para probar el log de servidor)
        // Logger::getInstance()->logServerError('Esto es un error de servidor de prueba');

        $data = [
            'title' => 'Prueba de sistema',
            'mensaje' => '¡El sistema MVC y el logging funcionan correctamente!'
        ];
        $this->render('test', $data);
    }
} 