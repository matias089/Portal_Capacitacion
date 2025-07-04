<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_07_RutSinGuion extends TestCase
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
    
    public function testInicioSesionFallidoRutSinGuion()
    {
        // No debería hacer consulta a la BD porque la validación falla antes
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // RUTs sin guión
        $rutsSinGuion = [
            '123456789',     // sin guión
            '12345678K',     // sin guión con K
            '87654321',      // sin guión
            '111111111',     // sin guión
            '123456780'      // sin guión
        ];
        
        foreach ($rutsSinGuion as $rut) {
            $result = $this->authService->authenticate($rut, 'Password123!');
            
            // Verificar que retorna error específico
            $this->assertIsArray($result);
            $this->assertArrayHasKey('error', $result);
            $this->assertEquals('El RUT debe contener un guión antes del dígito verificador', $result['error']);
            
            // Verificar que no se estableció sesión
            $this->assertArrayNotHasKey('rut', $_SESSION);
        }
    }
    
    public function testValidacionRutSinGuion()
    {
        $result = $this->authService->validateRut('15330771-7');
        $this->assertEquals('El RUT debe contener un guión antes del dígito verificador', $result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}

# prueba con inconsistencia de datos