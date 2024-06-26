const inputs = document.querySelectorAll('.codigoInput');

inputs.forEach((input, index) => {
  input.addEventListener('input', function(event) {
    if (this.value.length >= 1) {
      if (index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    }
  });

  input.addEventListener('keydown', function(event) {
    if (event.keyCode === 8 && this.value.length === 0) {
      if (index > 0) {
        inputs[index - 1].focus();
      }
    }
  });
});

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

function checkRefresh() {
if (localStorageAvailable()) {
const arrivedFromEnvioCorreo = localStorage.getItem('arrivedFromEnvioCorreo');
if (arrivedFromEnvioCorreo) {
  localStorage.setItem('refreshCount', '0');
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

const refreshCount = checkRefresh();
console.log("Número de recargas: ", refreshCount);

const intentosRestantes = 4 - refreshCount;
document.getElementById('intentos-restantes').innerText = intentosRestantes;

if (intentosRestantes <= 0) {
alert("Se te han agotado los intentos. Serás redirigido al inicio de sesión.");

setTimeout(function() {
window.location.href = 'login.php';
}, 2500); 
}