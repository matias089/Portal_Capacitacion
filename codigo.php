
<?php
//echo $rut;
session_start();
// Verificar si el usuario está intentando enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se recibió el código ingresado por el usuario
    if (isset($_POST['digit1']) && isset($_POST['digit2']) && isset($_POST['digit3']) && isset($_POST['digit4']) && isset($_POST['digit5'])) {
        // Recuperar los valores de los campos de entrada
        $digit1 = $_POST['digit1'];
        $digit2 = $_POST['digit2'];
        $digit3 = $_POST['digit3'];
        $digit4 = $_POST['digit4'];
        $digit5 = $_POST['digit5'];
        // Concatenar los dígitos en una sola cadena
        $codigo2 = $digit1 . $digit2 . $digit3 . $digit4 . $digit5;
        //echo $rut;
        $codigo_generado = $_SESSION['codigo_recuperacion'];
        
        $rut = $_SESSION["rut"];

        ob_start(); // Iniciar el buffer de salida
        var_dump($rut);
        $output = ob_get_clean(); // Capturar la salida del buffer y limpiarlo
        
        // Verificar si los códigos coinciden
        if ($codigo2 == $codigo_generado) {
          // Redirigir a newpw.php con el valor de $rut como parámetro en la URL
          header("Location: newpw.php?rut=" . urlencode($rut));
          exit();
        } else {
            // Los códigos no coinciden, mostrar un mensaje de error
            $error_message = "El código ingresado es incorrecto. Por favor, inténtalo de nuevo.";
        }
    } else {
        // Si no se recibieron todos los dígitos, muestra un mensaje de error
        $error_message = "Por favor, ingresa todos los dígitos del código de recuperación.";
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/codigo.css">
</head>
<body>
<style>
            .inputBox {
      display: flex;
    }

    .codigoInput {
      width: 40px;
      height: 40px;
      font-size: 24px;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 5px;
      outline: none;
      margin-right: 10px;
    }
  </style>
    <section>
        <div class="color"></div>
        <div class="color"></div>
        <div class="color"></div>
        <div class="box">
            <div class="square" style="--i:0;"></div>
            <div class="square" style="--i:1;"></div>
            <div class="square" style="--i:2;"></div>
            <div class="square" style="--i:3;"></div>
            <div class="square" style="--i:4;"></div>
            <div class="square" style="--i:5;"></div>
            <div class="square" style="--i:6;"></div>
            <div class="container">
        <div class="form">
            <h2>Ingresa el código de recuperación</h2>
            <?php if (isset($error_message)) : ?>
                <p  class="alert alert-danger" role="alert"><?php echo $error_message; ?></p>
            <?php endif; ?> 
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="inputBox">
                  <input type="text" class="codigoInput" name="digit1" maxlength="1" autofocus required autocomplete="off">
                  <input type="text" class="codigoInput" name="digit2" maxlength="1" required autocomplete="off">
                  <input type="text" class="codigoInput" name="digit3" maxlength="1" required autocomplete="off">
                  <input type="text" class="codigoInput" name="digit4" maxlength="1" required autocomplete="off">
                  <input type="text" class="codigoInput" name="digit5" maxlength="1" required autocomplete="off">
              </div>
            <div class="inputBox">
                <input type="submit" value="Validar Codigo" />
            </div> 
            </form>
            <div align="center"> 
              <p id="timer">Tiempo restante: 5:00</p>
              <p id="intentos">Intentos restantes: <span id="intentos-restantes">3</span></p> 
            </div>
        </div>
    </div>

    <script src="/Portal_Capacitacion/templates/js/codigo.js">
  </script>  
        </div>
    </section>
</body>
</html>
