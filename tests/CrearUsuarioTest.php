<?php

use PHPUnit\Framework\TestCase;

class CrearUsuarioTest extends TestCase
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
    }

    public function testInsertarUsuario()
    {
        $rut = '12.345.678-9';
        $contrasena = 'Password123!';
        $tipo_usuario = 'Usuario';
        $correo = 'test@example.com';
        $empresa = 'Coval';
        $nombre = 'Juan Pérez';
        $area = 'Ventas';
        $cargo = 'Gerente';

        $sql = "INSERT INTO usuarios (rut, contrasena, tipo_usuario, correo, empresa, nombre, area, cargo)
                VALUES (:rut, :contrasena, :tipo_usuario, :correo, :empresa, :nombre, :area, :cargo)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':rut', $rut);
        $stmt->bindParam(':contrasena', $contrasena);
        $stmt->bindParam(':tipo_usuario', $tipo_usuario);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':area', $area);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->execute();

        // Verifica que el usuario se haya insertado correctamente
        $result = $this->pdo->query("SELECT * FROM usuarios WHERE rut = '12.345.678-9'");
        $usuario = $result->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals($rut, $usuario['rut']);
        $this->assertEquals($contrasena, $usuario['contrasena']);
        $this->assertEquals($tipo_usuario, $usuario['tipo_usuario']);
        $this->assertEquals($correo, $usuario['correo']);
        $this->assertEquals($empresa, $usuario['empresa']);
        $this->assertEquals($nombre, $usuario['nombre']);
        $this->assertEquals($area, $usuario['area']);
        $this->assertEquals($cargo, $usuario['cargo']);
    }
}

?>