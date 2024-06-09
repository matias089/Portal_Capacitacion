<?php
echo $rut;
// Incluye el archivo de conexión a la base de datos
/*
include '/db/db.php';

// Verifica si se enviaron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén la nueva contraseña desde el formulario
    $new_password = $_POST['new_password'];

    // Verifica si la contraseña cumple con las restricciones
    if (!validatePassword($new_password)) {
        echo "La contraseña debe tener al menos 8 caracteres, un carácter especial, una letra mayúscula y un número.";
        exit; // Detiene la ejecución del script si la contraseña no cumple con los requisitos
    }

    // Obtén el ID del usuario (por ejemplo, desde la sesión)
    //$rut = $_SESSION['rut'];

    // Hashea la contraseña antes de almacenarla en la base de datos (cambia 'tu_algoritmo' por el algoritmo de hash que desees usar, por ejemplo, PASSWORD_DEFAULT)
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Actualiza la contraseña del usuario en la base de datos
    $query = "UPDATE usuarios SET contrasena = '$hashed_password' WHERE id = '$user_id'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo "Contraseña actualizada correctamente.";
    } else {
        echo "Error al actualizar la contraseña.";
    }

    // Cierra la conexión a la base de datos
    pg_close($conn);
}

// Función para validar la contraseña con las restricciones especificadas
function validatePassword($password) {
    // Expresiones regulares para verificar requisitos de la contraseña
    $specialChars = "/[!@#$%^&*(),.?\":{}|<>]/";
    $uppercaseChars = "/[A-Z]/";
    $numberChars = "/[0-9]/";

    // Verifica las restricciones de la contraseña
    if (strlen($password) < 8 || !preg_match($specialChars, $password) || !preg_match($uppercaseChars, $password) || !preg_match($numberChars, $password)) {
        return false;
    }

    return true;
}*/
?>
