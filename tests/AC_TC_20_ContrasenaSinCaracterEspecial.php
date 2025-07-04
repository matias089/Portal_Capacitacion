<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_20_ContrasenaSinCaracterEspecial extends TestCase
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
    
    public function testInicioSesionFallidoSinCaracterEspecial()
    {
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // Validar directamente la contraseña
        $result = $this->authService->validatePassword('Password1');
        
        $this->assertEquals('La contraseña debe contener al menos un carácter especial', $result);
        
        // Ahora probamos el método authenticate con un RUT válido
        $result = $this->authService->authenticate('12345678-5', 'Password1');
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('error', $result);
        $this->assertEquals('La contraseña debe contener al menos un carácter especial', $result['error']);
    }
    
    public function testValidacionContrasenaConCaracterEspecial()
    {
        $result = $this->authService->validatePassword('Password123!');
        $this->assertTrue($result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}