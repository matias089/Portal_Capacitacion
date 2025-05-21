<?php

use PHPUnit\Framework\TestCase;

class AsignacionCursosTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Configura una conexión a una base de datos de prueba
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE asignaciones (rut TEXT, curso TEXT)");
    }

    public function testAsignacionCurso()
    {
        $rut = '12345678-9';
        $curso = 'Curso 1';

        $sql = "INSERT INTO asignaciones (rut, curso) VALUES (?, ?)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$rut, $curso]);

        // Verifica que la asignación se haya insertado correctamente
        $stmt = $this->pdo->query("SELECT * FROM asignaciones WHERE rut = '$rut' AND curso = '$curso'");
        $asignacion = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals($rut, $asignacion['rut']);
        $this->assertEquals($curso, $asignacion['curso']);
    }
}

?>