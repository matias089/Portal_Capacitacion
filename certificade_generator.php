<?php
// Inicia la sesión si no está iniciada
session_start();
include 'error_control.php';
include 'db/db.php'; // Incluye los datos de conexión a la base de datos

// Verifica si el usuario está logueado
if (!isset($_SESSION['tipo_usuario'])) {
    // Si el usuario no está logueado, redirige a la página de login
    header("Location: portada.html");
    exit(); // Es importante salir del script después de redirigir
}

// Verifica si 'nombre' está establecido en la sesión
if (!isset($_SESSION['nombre'])) {
    die('Error: El nombre del usuario no está establecido en la sesión.');
}

// Verifica si 'rut' está establecido en la sesión
if (!isset($_SESSION['rut'])) {
    die('Error: El RUT del usuario no está establecido en la sesión.');
}

// Obtiene el nombre del curso desde la URL
if (!isset($_GET['nombre_cur'])) {
    die('Error: El nombre del curso no está especificado.');
}

$nombre_curso = $_GET['nombre_cur'];

// Conexión a la base de datos
$conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
if (!$conn) {
    die("Error de conexión: " . pg_last_error());
}

// Verifica si el curso está aprobado para el usuario
$rut_usuario = $_SESSION['rut'];
$query = "SELECT * FROM estado_examen WHERE rut = '$rut_usuario' AND nombre_cur = '$nombre_curso' AND estado = 'Aprobado'";
$result = pg_query($conn, $query);
if (!$result || pg_num_rows($result) == 0) {
    die('Error: El curso no está aprobado para este usuario.');
}

// Obtiene el nombre del usuario desde la sesión
$nombre_usuario = $_SESSION['nombre'];

// Incluye la biblioteca FPDF
require(__DIR__ . '/fpdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        // Cargar imagen de la plantilla del certificado
        $this->Image(__DIR__ . '/templates/img/certificado_plantilla.png', 0, 0, 900/3.78, 636/3.78);
        $this->SetFont('Arial', 'B', 20);
        $this->SetTextColor(0, 0, 0);
    }

    function Footer()
    {
        // Posición a 1.5 cm del final
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    function Certificado($nombre_usuario, $rut_usuario, $nombre_curso)
    {
        // Añadir el título del curso
        $this->SetXY(20, 35);
        $this->SetFont('Arial', 'B', 24);
        $this->Cell(0, 10, $nombre_curso, 0, 1, 'C');

        // Añadir el nombre del usuario
        $this->SetXY(20, 83);
        $this->SetFont('Arial', 'B', 40);
        $this->Cell(0, 10, $nombre_usuario, 0, 1, 'C');

        // Añadir el RUT del usuario
        $this->SetXY(80, 120);
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 10, $rut_usuario, 0, 1, 'C');

        // Añadir el RUT del usuario
        $this->SetXY(-290, 120);
        $this->SetFont('Arial', 'B', 18);
        $this->Cell(0, 10, 'Nevada E-Learning', 0, 1, 'C');
    }
}

// Crear una instancia del PDF
$pdf = new PDF('L', 'mm', array(900/3.78, 636/3.78)); // Ancho y alto del PDF en milímetros (900/3.78, 636/3.78)
$pdf->AddPage();
$pdf->Certificado($nombre_usuario, $rut_usuario, $nombre_curso);

// Generar la salida del PDF
$pdf->Output('I', 'certificado_aprobacion.pdf');

// Cierra la conexión a la base de datos
pg_free_result($result);
pg_close($conn);
?>
