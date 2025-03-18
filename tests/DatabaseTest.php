<?php

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase {
    public function testConnection() {
        $host = 'localhost';
        $port = '5432';
        $dbname = 'Nevada_Learning';
        $user = 'postgres';
        $password = '12345678';
        $db = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
        $this->assertNotFalse($conn, "La conexión a la base de datos falló.");
    }
    }

?>