<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;
use PortalCapacitacion\AuthService;

class inicioSesionExitosa extends TestCase
{
    private $mockPdo;
    private $authService;
    
    protected function setUp(): void
    {
        // Mock de la conexión PDO
        $this->mockPdo = $this->createMock(PDO::class);
        
        // Crear instancia del servicio con el mock
        $this->authService = new AuthService($this->mockPdo);
        
        // Inicializar sesión para pruebas
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = []; // Limpiar sesión antes de cada test
    }
    
    public function testInicioSesionValido()
    {
        // Configurar mock para éxito
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->method('rowCount')->willReturn(1);
        $mockStatement->method('fetch')->willReturn([
            'rut' => '12345678-9',
            'nombre' => 'Usuario Test',
            'tipo_usuario' => 'admin',
            'empresa' => 'Test Corp'
        ]);
        
        // Configurar expectativas del mock usando with() individual
        $mockStatement->expects($this->exactly(2))
                     ->method('bindParam')
                     ->willReturnCallback(function($param, $value) {
                         static $callCount = 0;
                         $callCount++;
                         
                         if ($callCount === 1) {
                             $this->assertEquals(':rut', $param);
                             $this->assertEquals('12345678-9', $value);
                         } elseif ($callCount === 2) {
                             $this->assertEquals(':password', $param);
                             $this->assertEquals('Passw0rd!', $value);
                         }
                         
                         return true;
                     });
        
        $mockStatement->expects($this->once())
                     ->method('execute');
        
        $this->mockPdo->expects($this->once())
                     ->method('prepare')
                     ->with($this->stringContains('SELECT rut, nombre, tipo_usuario, empresa FROM usuarios'))
                     ->willReturn($mockStatement);
        
        // Ejecutar autenticación
        $result = $this->authService->authenticate('12345678-9', 'Passw0rd!');
        
        // Verificaciones
        $this->assertTrue($result);
        $this->assertArrayHasKey('rut', $_SESSION);
        $this->assertEquals('12345678-9', $_SESSION['rut']);
        $this->assertEquals('Usuario Test', $_SESSION['nombre']);
        $this->assertEquals('admin', $_SESSION['tipo_usuario']);
        $this->assertEquals('Test Corp', $_SESSION['empresa']);
    }
    
    public function testInicioSesionInvalido()
    {
        // Configurar mock para fallo
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->method('rowCount')->willReturn(0);
        
        $mockStatement->expects($this->exactly(2))
                     ->method('bindParam')
                     ->willReturnCallback(function($param, $value) {
                         static $callCount = 0;
                         $callCount++;
                         
                         if ($callCount === 1) {
                             $this->assertEquals(':rut', $param);
                             $this->assertEquals('99999999-9', $value);
                         } elseif ($callCount === 2) {
                             $this->assertEquals(':password', $param);
                             $this->assertEquals('incorrecto', $value);
                         }
                         
                         return true;
                     });
        
        $mockStatement->expects($this->once())
                     ->method('execute');
        
        $this->mockPdo->expects($this->once())
                     ->method('prepare')
                     ->willReturn($mockStatement);
        
        // Ejecutar autenticación
        $result = $this->authService->authenticate('99999999-9', 'incorrecto');
        
        // Verificaciones
        $this->assertFalse($result);
        $this->assertArrayNotHasKey('rut', $_SESSION);
    }
    
    public function testInicioSesionConExcepcion()
    {
        // Configurar mock para lanzar excepción
        $this->mockPdo->expects($this->once())
                     ->method('prepare')
                     ->willThrowException(new \PDOException('Error de base de datos'));
        
        // Verificar que se lanza la excepción
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('Error de base de datos');
        
        $this->authService->authenticate('12345678-9', 'Passw0rd!');
    }
    
    protected function tearDown(): void
    {
        // Limpiar después de cada prueba
        $_SESSION = [];
    }
}