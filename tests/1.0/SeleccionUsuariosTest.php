<?php

use PHPUnit\Framework\TestCase;

class SeleccionUsuariosTest extends TestCase
{
    public function testSeleccionUsuarios()
    {
        // Simula la selección de usuarios
        $_POST['ruts_seleccionados'] = ['12345678-9', '98765432-1'];

        $this->assertContains('12345678-9', $_POST['ruts_seleccionados']);
        $this->assertContains('98765432-1', $_POST['ruts_seleccionados']);
    }
}

?>