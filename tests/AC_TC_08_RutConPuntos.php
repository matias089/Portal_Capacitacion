<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_08_RutConPuntos extends TestCase
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
    
    public function testInicioSesionFallidoRutConPuntos()
    {
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // RUTs con puntos
        $rutsConPuntos = [
            '12.345.678-9',   // formato con puntos
            '1.234.567-8',    // formato con puntos
            '11.111.111-1',   // formato con puntos
            '8.765.432-1',    // formato con puntos
            '12.345.678-K'    // formato con puntos y K
        ];
        
        foreach ($rutsConPuntos as $rut) {
            $result = $this->authService->authenticate($rut, 'Password123!');
            
            $this->assertIsArray($result);
            $this->assertArrayHasKey('error', $result);
            $this->assertEquals('El RUT no debe contener puntos', $result['error']);
            $this->assertArrayNotHasKey('rut', $_SESSION);
        }
    }
    
    public function testValidacionRutConPuntos()
    {
        $result = $this->authService->validateRut('12.345.678-9');
        $this->assertEquals('El RUT no debe contener puntos', $result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}