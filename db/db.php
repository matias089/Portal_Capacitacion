<?php
// Datos de conexión a la base de datos PostgreSQL
/*
$host = 'localhost';
$port = '5432';
$dbname = 'PortalCapacitacion';
$user = 'postgres';
$password = '1234';
*/
/*echo  "Conectando a la base de datos...<br>";
echo file_get_contents(dirname(__FILE__)."/conexion.html"); // Mostrar el mensaje de bienvenida
try {
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
} catch (PDOException $e) {
    echo "Error en la conexión: " . $e->getMessage();
    die();
}*/


$host = 'localhost';
$port = '5432';
$dbname = 'Nevada_Learning';
$user = 'postgres';
$password = '12345678';

// Conexión a la base de datos
$db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");


?>
