<?php

use PHPUnit\Framework\TestCase;

class PaginacionTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Configura una conexión a una base de datos de prueba
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE usuarios (empresa TEXT, area TEXT, cargo TEXT, rut TEXT, nombre TEXT)");

        // Inserta 15 usuarios de prueba
        for ($i = 1; $i <= 15; $i++) {
            $this->pdo->exec("INSERT INTO usuarios (empresa, area, cargo, rut, nombre) VALUES 
                              ('Empresa $i', 'Área $i', 'Cargo $i', '12345678-$i', 'Usuario $i')");
        }
    }

    public function testPaginacion()
    {
        $registrosPorPagina = 10;
        $paginaActual = 1;
        $offset = ($paginaActual - 1) * $registrosPorPagina;

        $stmt = $this->pdo->prepare("SELECT empresa, area, cargo, rut, nombre FROM usuarios LIMIT ? OFFSET ?");
        $stmt->execute([$registrosPorPagina, $offset]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(10, $usuarios);
    }
}

?>