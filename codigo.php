
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

    <script>
    const inputs = document.querySelectorAll('.codigoInput');

    inputs.forEach((input, index) => {
      input.addEventListener('input', function(event) {
        if (this.value.length >= 1) {
          // Enfocar automáticamente el siguiente campo de entrada
          if (index < inputs.length - 1) {
            inputs[index + 1].focus();
          }
        }
      });

      input.addEventListener('keydown', function(event) {
        if (event.keyCode === 8 && this.value.length === 0) {
          // Retroceder al campo de entrada anterior al borrar
          if (index > 0) {
            inputs[index - 1].focus();
          }
        }
      });
    });

    // Temporizador de 5 minutos
    var tiempoRestante = 299;
    var timerElement = document.getElementById('timer');

    var countdown = setInterval(function() {
      var minutos = Math.floor(tiempoRestante / 60);
      var segundos = tiempoRestante % 60;

      if (segundos < 10) {
        segundos = '0' + segundos;
      }

      timerElement.textContent = 'Tiempo restante: ' + minutos + ':' + segundos;

      if (tiempoRestante == 0) {
        clearInterval(countdown);
        window.location.href = 'login.php';
      } else {
        tiempoRestante--;
      }
    }, 1000);

    // Función para verificar si localStorage es compatible con el navegador
function localStorageAvailable() {
  try {
    const x = '__storage_test__';
    localStorage.setItem(x, x);
    localStorage.removeItem(x);
    return true;
  } catch (e) {
    return false;
  }
}

// Función para incrementar y obtener el contador de recargas
function checkRefresh() {
  if (localStorageAvailable()) {
    // Verificar si el usuario llegó desde envio_correo.php
    const arrivedFromEnvioCorreo = localStorage.getItem('arrivedFromEnvioCorreo');
    if (arrivedFromEnvioCorreo) {
      // Reiniciar los intentos si el usuario llegó desde envio_correo.php
      localStorage.setItem('refreshCount', '0');
      // Eliminar la bandera para indicar que ya se ha reiniciado
      localStorage.removeItem('arrivedFromEnvioCorreo');
    }

    let count = localStorage.getItem('refreshCount');
    count = count ? parseInt(count) + 1 : 1;
    localStorage.setItem('refreshCount', count);
    return count;
  } else {
    return "localStorage no está disponible en este navegador.";
  }
}

// Llama a la función para verificar la recarga y muestra el contador
const refreshCount = checkRefresh();
console.log("Número de recargas: ", refreshCount);

// Actualiza el contador de intentos restantes en el HTML
const intentosRestantes = 4 - refreshCount;
document.getElementById('intentos-restantes').innerText = intentosRestantes;

// Verifica si no quedan intentos restantes y redirige a login.php
if (intentosRestantes <= 0) {
  // Agrega un mensaje para notificar al usuario
  alert("Se te han agotado los intentos. Serás redirigido al inicio de sesión.");
  
  // Redirige a la página de inicio de sesión después de un breve retraso
  setTimeout(function() {
    window.location.href = 'login.php';
  }, 2500); // Tiempo en milisegundos (2,5 segundos)
}

    /*if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
    window.location.href = "pwreset.html";
    }*/
  </script>
        </div>
    </section>
</body>
</html>
