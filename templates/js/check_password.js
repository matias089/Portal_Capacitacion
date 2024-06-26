document.addEventListener('DOMContentLoaded', function() {
    var closeButton = document.getElementById('closeButton');
    closeButton.addEventListener('click', function() {
        alert("Se cerrará tu sesión debido a que tu contraseña ha expirado.");
        window.location.href = 'logout.php';
    });
});