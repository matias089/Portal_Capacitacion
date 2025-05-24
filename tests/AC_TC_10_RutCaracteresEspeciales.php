<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PortalCapacitacion\AuthService;

class AC_TC_10_RutCaracteresEspeciales extends TestCase
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
    
    public function testInicioSesionFallidoRutCaracteresEspeciales()
    {
        $this->mockPdo->expects($this->never())->method('prepare');
        
        // RUTs con caracteres especiales o símbolos
        $rutsCaracteresEspeciales = [
            '123a456&78-9',     
        ];
        
        foreach ($rutsCaracteresEspeciales as $rut) {
            $result = $this->authService->authenticate($rut, 'Password123!');
            
            $this->assertIsArray($result);
            $this->assertArrayHasKey('error', $result);
            $this->assertEquals('El RUT contiene caracteres no válidos', $result['error']);
            $this->assertArrayNotHasKey('rut', $_SESSION);
        }
    }
    
    public function testValidacionRutCaracteresEspeciales()
    {
        $result = $this->authService->validateRut('123a456&78-9');
        $this->assertEquals('El RUT contiene caracteres no válidos', $result);
    }
    
    public function testValidacionRutConLetrasInvalidas()
    {
        $result = $this->authService->validateRut('abcd567-9');
        $this->assertEquals('El RUT contiene caracteres no válidos', $result);
    }
    
    public function testValidacionRutConEspacios()
    {
        $result = $this->authService->validateRut('1234567 -9');
        $this->assertEquals('El RUT contiene caracteres no válidos', $result);
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}