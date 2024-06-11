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