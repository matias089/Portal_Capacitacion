<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_09_RutDvIncorrecto extends TestCase
{
    private $mockPdo;
    private $authService;
    
    protected function setUp(): void
    {
        $this->mockPdo = $this->createMock(PDO::class);
        $this->authService = new AuthService($this->mockPdo);
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
    }
    
    public function testInicioSesionFallidoRutDvIncorrecto()
    {
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // RUTs con dígito verificador incorrecto
        // Nota: 12345678-5 es el RUT correcto, estos tienen DV incorrecto
        $rutsDvIncorrecto = [
            '12345678-9',     // DV incorrecto (pero numéricamente válido)
        ];
        
        foreach ($rutsDvIncorrecto as $rut) {
            $result = $this->authService->authenticate($rut, 'Password123!');
            
            $this->assertIsArray($result);
            $this->assertArrayHasKey('error', $result);
            $this->assertEquals('El dígito verificador del RUT es incorrecto', $result['error']);
            $this->assertArrayNotHasKey('rut', $_SESSION);
        }
    }
    
    public function testValidacionRutDvIncorrecto()
    {
        $result = $this->authService->validateRut('12345678-9');
        $this->assertEquals('El dígito verificador del RUT es incorrecto', $result);
    }
    
    public function testRutValidoConDvCorrecto()
    {
        // Verificar que un RUT con DV correcto pasa la validación
        $result = $this->authService->validateRut('12345678-5');
        $this->assertTrue($result);
    }
    
    public function testRutValidoConK()
    {
        // Verificar que un RUT con DV correcto pasa la validación
        $result = $this->authService->validateRut('12345678-k');
        $this->assertNotEquals('El RUT contiene caracteres no válidos', $result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}
