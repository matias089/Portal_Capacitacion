<?php
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    private $authService;
    private $mockConn;
    
    protected function setUp(): void
    {
        // Crear un mock de la conexión a PostgreSQL
        $this->mockConn = $this->createMock(\PDO::class);
        
        // Inicializar el servicio de autenticación con el mock
        $this->authService = new AuthService($this->mockConn);
    }
    
    public function testInicioSesionExitosoConCredencialesValidas()
    {
        // Configurar datos de prueba
        $rutValido = '12345678-9';
        $passwordValido = 'Contr4señ@';
        
        // Mock de la consulta a la base de datos
        $mockStatement = $this->createMock(\PDOStatement::class);
        
        // Configurar el mock para simular un usuario encontrado
        $mockStatement->method('rowCount')->willReturn(1);
        $mockStatement->method('fetch')->willReturn([
            'rut' => $rutValido,
            'nombre' => 'Usuario de Prueba',
            'tipo_usuario' => 'admin',
            'empresa' => 'Empresa Test'
        ]);
        
        // Configurar el mock de la conexión para devolver el statement mockeado
        $this->mockConn->method('prepare')->willReturn($mockStatement);
        
        // Ejecutar la autenticación
        $resultado = $this->authService->autenticarUsuario($rutValido, $passwordValido);
        
        // Verificaciones
        $this->assertArrayHasKey('success', $resultado);
        $this->assertTrue($resultado['success']);
        $this->assertArrayHasKey('usuario', $resultado);
        $this->assertEquals($rutValido, $resultado['usuario']['rut']);
    }
    
    public function testValidacionRut()
    {
        // RUT válidos
        $this->assertTrue($this->authService->validarRut('12345678-9'));
        $this->assertTrue($this->authService->validarRut('1234567-k'));
        
        // RUT inválidos
        $this->assertFalse($this->authService->validarRut('123456789')); // Sin guión
        $this->assertFalse($this->authService->validarRut('12.345.678-9')); // Con puntos
        $this->assertFalse($this->authService->validarRut('12345678-x')); // DV incorrecto
    }
    
    public function testValidacionPassword()
    {
        // Contraseña válida
        $this->assertTrue($this->authService->validarPassword('Contr4señ@'));
        
        // Contraseñas inválidas
        $this->assertFalse($this->authService->validarPassword('contra')); // Muy corta
        $this->assertFalse($this->authService->validarPassword('contraseña')); // Sin mayúscula ni números
        $this->assertFalse($this->authService->validarPassword('CONTRASEÑA1')); // Sin minúscula
        $this->assertFalse($this->authService->validarPassword('Contraseña1')); // Sin símbolo
    }
    
    public function testAutenticacionFallidaPorCredencialesInvalidas()
    {
        // Configurar datos de prueba
        $rutValido = '12345678-9';
        $passwordInvalido = 'password';
        
        // Mock de la consulta a la base de datos (sin resultados)
        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->method('rowCount')->willReturn(0);
        
        $this->mockConn->method('prepare')->willReturn($mockStatement);
        
        // Ejecutar la autenticación
        $resultado = $this->authService->autenticarUsuario($rutValido, $passwordInvalido);
        
        // Verificaciones
        $this->assertArrayHasKey('error', $resultado);
        $this->assertEquals('Usuario o contraseña incorrectos.', $resultado['error']);
    }
}
?>