<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PortalCapacitacion\AuthService;
use PDO;

class AuthServiceTest extends TestCase
{
    private $mockPdo;
    private $authService;
    
    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(PDO::class);
        $this->authService = new AuthService($this->mockPdo);
    }

    public function testValidateRutCorrecto()
    {
        $result = $this->authService->validateRut('21473665-9');
        $this->assertTrue($result['isValid']);
    }

    public function testValidateRutSinGuion()
    {
        $result = $this->authService->validateRut('214736659');
        $this->assertFalse($result['isValid']);
        $this->assertEquals('El RUT debe contener un guión antes del dígito verificador', $result['message']);
    }

    public function testValidateRutConPuntos()
    {
        $result = $this->authService->validateRut('21.473.665-9');
        $this->assertFalse($result['isValid']);
        $this->assertEquals('El RUT no debe contener puntos', $result['message']);
    }

    public function testValidateRutVacio()
    {
        $result = $this->authService->validateRut('');
        $this->assertFalse($result['isValid']);
        $this->assertEquals('El RUT no puede estar vacío', $result['message']);
    }

    public function testValidateRutCaracteresInvalidos()
    {
        $result = $this->authService->validateRut('2147A665-9');
        $this->assertFalse($result['isValid']);
        $this->assertEquals('El RUT contiene caracteres no válidos', $result['message']);
    }

    public function testValidateRutConKMinuscula()
    {
        $result = $this->authService->validateRut('12345678-k');
        $this->assertFalse($result['isValid']);
        $this->assertEquals('El dígito verificador solo puede ser la letara K o numerico', $result['message']);
    }
}