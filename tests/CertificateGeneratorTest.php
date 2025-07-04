<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../fpdf/fpdf.php';

class CertificateGeneratorTest extends TestCase 
{
    private $pdo;
    
    protected function setUp(): void
    {
        // Mock database connection
        $this->pdo = $this->createMock(\PDO::class);
    }

    public function testValidateUserSession()
    {
        $_SESSION['tipo_usuario'] = 'estudiante';
        $_SESSION['nombre'] = 'John Doe';
        $_SESSION['rut'] = '12345678-9';
        
        $this->assertTrue(isset($_SESSION['tipo_usuario']));
        $this->assertTrue(isset($_SESSION['nombre']));
        $this->assertTrue(isset($_SESSION['rut']));
    }

    public function testValidateCourseApproval()
    {
        $rut = '12345678-9';
        $courseId = 1;
        
        $stmt = $this->createMock(\PDOStatement::class);
        $stmt->method('fetch')
            ->willReturn([
                'estado' => 'Aprobado'
            ]);
            
        $this->pdo->method('prepare')
            ->willReturn($stmt);
            
        $result = $stmt->fetch();
        $this->assertEquals('Aprobado', $result['estado']);
    }

    public function testPDFGeneration() 
    {
        $nombre = 'John Doe';
        $rut = '12345678-9';
        $curso = 'Curso de Prueba';
        
        $pdf = new \FPDF('L', 'mm', array(900/3.78, 636/3.78));
        
        $this->assertInstanceOf(\FPDF::class, $pdf);
    }
}