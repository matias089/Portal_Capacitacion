<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../funciones.php';

class CodeTest extends TestCase {
    public function testGenerarCodigo() {
        $codigo = generarCodigo();

        $this->assertIsInt($codigo, "El código generado no es un número entero");

        $this->assertGreaterThanOrEqual(10000, $codigo, "El código es menor que 10000");
        $this->assertLessThanOrEqual(99999, $codigo, "El código es mayor que 99999");
    }
}
?>