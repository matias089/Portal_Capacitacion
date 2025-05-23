<?php
namespace PortalCapacitacion\Tests;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOStatement;
use PortalCapacitacion\AuthService;

class inicioSesionExitosaSimple extends TestCase
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
    
    public function testInicioSesionValido()
    {
        // Configurar mock statement
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->method('rowCount')->willReturn(1);
        $mockStatement->method('fetch')->willReturn([
            'rut' => '12345678-9',
            'nombre' => 'Usuario Test',
            'tipo_usuario' => 'admin',
            'empresa' => 'Test Corp'
        ]);
        
        // Configurar que bindParam y execute sean llamados
        $mockStatement->expects($this->exactly(2))->method('bindParam');
        $mockStatement->expects($this->once())->method('execute');
        
        // Configurar PDO mock
        $this->mockPdo->expects($this->once())
                     ->method('prepare')
                     ->willReturn($mockStatement);
        
        // Ejecutar test
        $result = $this->authService->authenticate('12345678-9', 'Passw0rd!');
        
        // Verificaciones
        $this->assertTrue($result);
        $this->assertEquals('12345678-9', $_SESSION['rut']);
        $this->assertEquals('Usuario Test', $_SESSION['nombre']);
        $this->assertEquals('admin', $_SESSION['tipo_usuario']);
        $this->assertEquals('Test Corp', $_SESSION['empresa']);
    }
    
    public function testInicioSesionInvalido()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->method('rowCount')->willReturn(0);
        $mockStatement->expects($this->exactly(2))->method('bindParam');
        $mockStatement->expects($this->once())->method('execute');
        
        $this->mockPdo->expects($this->once())
                     ->method('prepare')
                     ->willReturn($mockStatement);
        
        $result = $this->authService->authenticate('99999999-9', 'incorrecto');
        
        $this->assertFalse($result);
        $this->assertEmpty($_SESSION);
    }
    
    public function testInicioSesionConExcepcion()
    {
        $this->mockPdo->expects($this->once())
                     ->method('prepare')
                     ->willThrowException(new \PDOException('Error de base de datos'));
        
        $this->expectException(\PDOException::class);
        $this->authService->authenticate('12345678-9', 'Passw0rd!');
    }
    
    protected function tearDown(): void
    {
        $_SESSION = [];
    }
}