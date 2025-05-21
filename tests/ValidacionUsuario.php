<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../ValidadorUsuario.php';

class ValidadorUsuarioTest extends TestCase {

    // === PRUEBAS PARA RUT ===

    public function testRutFormatoValido() {
        // Arrange
        $rut = "12345678-9";

        // Act
        $resultado = ValidadorUsuario::validarRut($rut);

        // Assert
        $this->assertTrue($resultado);
    }

    public function testRutSinGuionInvalido() {
        // Arrange
        $rut = "123456789";

        // Act
        $resultado = ValidadorUsuario::validarRut($rut);

        // Assert
        $this->assertFalse($resultado);
    }

    public function testRutConPuntosInvalido() {
        // Arrange
        $rut = "12.345.678-9";

        // Act
        $resultado = ValidadorUsuario::validarRut($rut);

        // Assert
        $this->assertFalse($resultado);
    }

    public function testRutConSimbolosOLetrasInvalido() {
        // Arrange
        $rut = "123a456&78-9";

        // Act
        $resultado = ValidadorUsuario::validarRut($rut);

        // Assert
        $this->assertFalse($resultado);
    }

    public function testRutVacioONuloInvalido() {
        // Arrange
        $rut = "";

        // Act
        $resultado = ValidadorUsuario::validarRut($rut);

        // Assert
        $this->assertFalse($resultado);
    }

    // === PRUEBAS PARA CONTRASEÑA ===

    public function testContrasenaValida() {
        // Arrange
        $contrasena = "Contr4señ@";

        // Act
        $resultado = ValidadorUsuario::validarContrasena($contrasena);

        // Assert
        $this->assertTrue($resultado);
    }

    public function testContrasenaMenosDe8Caracteres() {
        // Arrange
        $contrasena = "Pa0rd!";

        // Act
        $resultado = ValidadorUsuario::validarContrasena($contrasena);

        // Assert
        $this->assertFalse($resultado);
    }

    public function testContrasenaSinMayuscula() {
        // Arrange
        $contrasena = "p@ssword1!";

        // Act
        $resultado = ValidadorUsuario::validarContrasena($contrasena);

        // Assert
        $this->assertFalse($resultado);
    }

    public function testContrasenaSinCaracterEspecial() {
        // Arrange
        $contrasena = "Password1";

        // Act
        $resultado = ValidadorUsuario::validarContrasena($contrasena);

        // Assert
        $this->assertFalse($resultado);
    }

    public function testContrasenaSinNumero() {
        // Arrange
        $contrasena = "P@ssword!";

        // Act
        $resultado = ValidadorUsuario::validarContrasena($contrasena);

        // Assert
        $this->assertFalse($resultado);
    }

    public function testContrasenaVaciaONula() {
        // Arrange
        $contrasena = "";

        // Act
        $resultado = ValidadorUsuario::validarContrasena($contrasena);

        // Assert
        $this->assertFalse($resultado);
    }
}
