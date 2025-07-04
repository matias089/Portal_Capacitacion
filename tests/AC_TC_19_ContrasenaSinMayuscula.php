<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_19_ContrasenaSinMayuscula extends TestCase
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
    
    public function tetInicioSesionFallidoSinMayuscula()
    {
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // Usar un RUT válido para que pase la validación de RUT
        $result = $this->authService->validatePassword('password123!');
        
        $this->assertEquals('La contraseña debe contener al menos una letra mayúscula', $result);
        
        // Ahora probamos el método authenticate con un RUT válido
        $result = $this->authService->authenticate('12345678-5', 'password123!');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('La contraseña debe contener al menos una letra mayúscula', $result['error']);
    }
    
    public function testValidacionContrasenaConMayuscula()
    {
        $result = $this->authService->validatePassword('Password123!');
        $this->assertTrue($result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}