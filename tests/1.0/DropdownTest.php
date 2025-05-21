<?php

use PHPUnit\Framework\TestCase;

class DropdownTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Configura una conexión a una base de datos de prueba
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE cursos (nombre_cur TEXT)");
        $this->pdo->exec("CREATE TABLE usuarios (empresa TEXT, area TEXT, cargo TEXT, rut TEXT, nombre TEXT)");

        // Inserta datos de prueba
        $this->pdo->exec("INSERT INTO cursos (nombre_cur) VALUES ('Curso 1'), ('Curso 2')");
        $this->pdo->exec("INSERT INTO usuarios (empresa, area, cargo, rut, nombre) VALUES 
                          ('Empresa A', 'Área 1', 'Cargo 1', '12345678-9', 'Juan Pérez'),
                          ('Empresa B', 'Área 2', 'Cargo 2', '98765432-1', 'Ana Gómez')");
    }

    public function testDropdownCursos()
    {
        $stmt = $this->pdo->query("SELECT nombre_cur FROM cursos ORDER BY nombre_cur ASC");
        $cursos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $this->assertContains('Curso 1', $cursos);
        $this->assertContains('Curso 2', $cursos);
    }

    public function testDropdownEmpresas()
    {
        $stmt = $this->pdo->query("SELECT DISTINCT empresa FROM usuarios");
        $empresas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $this->assertContains('Empresa A', $empresas);
        $this->assertContains('Empresa B', $empresas);
    }
}

?>