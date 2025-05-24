<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_21_ContrasenaSinNumero extends TestCase
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
    
    public function testInicioSesionFallidoSinNumero()
    {
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // Validar directamente la contraseña
        $result = $this->authService->validatePassword('Password!');
        
        $this->assertEquals('La contraseña debe contener al menos un número', $result);
        
        // Ahora probamos el método authenticate con un RUT válido
        $result = $this->authService->authenticate('12345678-5', 'Password!');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('La contraseña debe contener al menos un número', $result['error']);
    }
    
    public function testValidacionContrasenaConNumero()
    {
        $result = $this->authService->validatePassword('Password123!');
        $this->assertTrue($result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}