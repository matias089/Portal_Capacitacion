function validateForm() {
    var password = document.getElementById("contrasena").value;

    // Expresiones regulares para verificar requisitos de la contraseña
    var specialChars = /[!@#$%^&*(),.-_?":{}|<>]/g; // Caracteres especiales
    var uppercaseChars = /[A-Z]/g; // Letras mayúsculas
    var numberChars = /[0-9]/g; // Números

    if (
      password.length < 8 || // Mínimo 8 caracteres
      !specialChars.test(password) || // Al menos un carácter especial
      !uppercaseChars.test(password) || // Al menos una letra mayúscula
      !numberChars.test(password) // Al menos un número
    ) {
      alert("La contraseña debe tener al menos 8 caracteres, un carácter especial, una letra mayúscula y un número.");
      return false; // Evitar enviar el formulario si la validación falla
    }

    return true; // Enviar el formulario si la validación es exitosa
  } 