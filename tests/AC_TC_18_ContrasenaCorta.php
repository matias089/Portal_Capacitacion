<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_18_ContrasenaCorta extends TestCase
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
    
    public function testInicioSesionFallidoContrasenaCorta()
    {
        // No debería hacer consulta a la BD porque la validación falla antes
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // Probar contraseñas de diferentes longitudes menores a 8
        $contrasenasCortas = [
            'Abc1!',      // 5 caracteres
            'Abc12!',     // 6 caracteres  
            'Abc123!',    // 7 caracteres
            'A1!',        // 3 caracteres
            'Ab1!'        // 4 caracteres
        ];
        
        foreach ($contrasenasCortas as $contrasena) {
            $result = $this->authService->authenticate('12345678-9', $contrasena);
            
            // Verificar que retorna error específico
            $this->assertIsArray($result);
            $this->assertArrayHasKey('error', $result);
            $this->assertEquals('La contraseña debe tener al menos 8 caracteres', $result['error']);
            
            // Verificar que no se estableció sesión
            $this->assertArrayNotHasKey('rut', $_SESSION);
        }
    }
    
    public function testValidacionContrasenaCorta()
    {
        $result = $this->authService->validatePassword('Abc1!');
        $this->assertEquals('La contraseña debe tener al menos 8 caracteres', $result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}