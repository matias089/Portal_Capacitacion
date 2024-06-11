document.addEventListener('DOMContentLoaded', function() {
    // Agregar un listener para el evento de cierre de la alerta
    var closeButton = document.getElementById('closeButton');
    closeButton.addEventListener('click', function() {
        // Mostrar mensaje antes de redirigir
        alert("Se cerrará tu sesión debido a que tu contraseña ha expirado.");
        // Redirigir al usuario a logout.php
        window.location.href = 'logout.php';
    });
});