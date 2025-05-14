<?php
require 'vendor/autoload.php';
include 'error_control.php';

require 'vendor/phpmailer/phpmailer/src/Exception.php';
require 'vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'vendor/phpmailer/phpmailer/src/SMTP.php';

if (isset($_POST['rut'])) {
    $rut = $_POST['rut'];

    include 'db/db.php';
    $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");

    $query = "SELECT correo FROM usuarios WHERE rut = $1";
    $result = pg_query_params($conn, $query, array($rut));

    if (!$result) {
        echo "Error en la consulta: " . pg_last_error($conn);
    }

    if (pg_num_rows($result) > 0) {
        $usuario = pg_fetch_assoc($result);
        $correo = $usuario['correo'];

        $codigo = mt_rand(10000, 99999);

        session_start();
        $_SESSION["rut"] = $rut;
        $_SESSION['codigo_recuperacion'] = $codigo;

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.sendgrid.net';
        $mail->SMTPAuth   = true;
        $mail->Username = 'apikey'; // Literalmente la palabra 'apikey'
        $mail->Password = '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'; // API Key de SendGrid
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('acecorp-projects@outlook.es', 'No-Reply ACE Corporation');
        $mail->addAddress($correo);

        $mail->isHTML(true);
        $mail->Subject = 'Código de Recuperación de Clave';
        $mail->Body    = 'Tu código de recuperación de clave es: ' . $codigo ;
        
        if ($mail->send()) {
            echo '<script>window.localStorage.setItem("arrivedFromEnvioCorreo", "true");</script>';
            echo '<script>alert("Se ha enviado un código de recuperación a tu correo electrónico."); window.location.href = "codigo.php";</script>';
        } else {
            echo '<script>alert("Error al enviar el correo electrónico: ' . $mail->ErrorInfo . '"); window.location.href = "pwreset.php";</script>';
        }
    } else {
        echo '<script>alert("No se encontró ningún usuario registrado con ese RUT."); window.location.href = "pwreset.php";</script>';
    }

    pg_close($conn);
} else {
    header('Location: pwreset.php');
    exit;
}
?>