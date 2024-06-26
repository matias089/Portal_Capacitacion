function validateForm() {
    var password = document.getElementById("contrasena").value;

    var specialChars = /[!@#$%^&*(),.-_?":{}|<>]/g;
    var uppercaseChars = /[A-Z]/g;
    var numberChars = /[0-9]/g;

    if (
      password.length < 8 ||
      !specialChars.test(password) ||
      !uppercaseChars.test(password) || 
      !numberChars.test(password) 
    ) {
      alert("La contraseña debe tener al menos 8 caracteres, un carácter especial, una letra mayúscula y un número.");
      return false;
    }
    return true; 
  } 