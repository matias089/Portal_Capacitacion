<?php

use PHPUnit\Framework\TestCase;

class EliminarUsuarioTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Configura una conexión a una base de datos de prueba
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec("CREATE TABLE usuarios (
            id INTEGER PRIMARY KEY,
            rut TEXT,
            contrasena TEXT,
            tipo_usuario TEXT,
            correo TEXT,
            empresa TEXT,
            nombre TEXT,
            area TEXT,
            cargo TEXT
        )");

        // Inserta un usuario de prueba
        $this->pdo->exec("INSERT INTO usuarios (rut, contrasena, tipo_usuario, correo, empresa, nombre, area, cargo)
                        VALUES ('12.345.678-9', 'Password123!', 'Usuario', 'test@example.com', 'Coval', 'Juan Pérez', 'Ventas', 'Gerente')");
    }

    public function testEliminarUsuario()
    {
        $sql = "DELETE FROM usuarios WHERE rut = '12.345.678-9'";
        $this->pdo->exec($sql);

        // Verifica que el usuario se haya eliminado correctamente
        $result = $this->pdo->query("SELECT * FROM usuarios WHERE rut = '12.345.678-9'");
        $usuario = $result->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($usuario);
    }
}

?>