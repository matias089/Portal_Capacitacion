<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../validator/validator_crear_usuario.php';

class ValidacionUsuario extends TestCase
{
    public function testRutValido()
    {
        $this->assertTrue(Validator::validarRut("12345678-9"));
    }

    public function testRutSinGuion()
    {
        $this->assertFalse(Validator::validarRut("123456789"));
    }

    public function testRutConPuntos()
    {
        $this->assertFalse(Validator::validarRut("12.345.678-9"));
    }

    public function testRutConSimbolos()
    {
        $this->assertFalse(Validator::validarRut("123a456&78-9"));
    }

    public function testContrasenaValida()
    {
        $this->assertTrue(Validator::validarContrasena("Contr4señ@"));
    }

    public function testContrasenaCorta()
    {
        $this->assertFalse(Validator::validarContrasena("passw0rd!"));
    }

    public function testContrasenaSinMayuscula()
    {
        $this->assertFalse(Validator::validarContrasena("p@ssword1"));
    }

    public function testContrasenaSinSimbolo()
    {
        $this->assertFalse(Validator::validarContrasena("Password1"));
    }

    public function testContrasenaSinNumero()
    {
        $this->assertFalse(Validator::validarContrasena("P@ssword!"));
    }

    public function testNombreNoVacio()
    {
        $this->assertTrue(Validator::validarNombre("David Hernán Onofre"));
    }

    public function testNombreVacio()
    {
        $this->assertFalse(Validator::validarNombre(""));
    }

    public function testCorreoValido()
    {
        $this->assertTrue(Validator::validarCorreo("matias@duocuc.cl"));
    }

    public function testCorreoConArrobaAlFinal()
    {
        $this->assertFalse(Validator::validarCorreo("matias@duocuc.cl@"));
    }

    public function testCorreoSinArroba()
    {
        $this->assertFalse(Validator::validarCorreo("matiasduocuc.cl"));
    }

    public function testEmpresaValida()
    {
        $this->assertTrue(Validator::validarEmpresa("Nevada"));
    }

    public function testEmpresaVacia()
    {
        $this->assertFalse(Validator::validarEmpresa(""));
    }

    public function testTipoUsuarioValido()
    {
        $this->assertTrue(Validator::validarTipoUsuario("Administrador"));
    }

    public function testTipoUsuarioVacio()
    {
        $this->assertFalse(Validator::validarTipoUsuario(""));
    }

    public function testCargoValido()
    {
        $this->assertTrue(Validator::validarCargo("Analista"));
    }

    public function testCargoVacio()
    {
        $this->assertFalse(Validator::validarCargo(""));
    }
}
