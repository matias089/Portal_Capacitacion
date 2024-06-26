function validateForm() {
    var password = document.getElementById("new_password1").value;

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
  function validarContraseña() {
      var contraseña = document.getElementById("new_password1").value;
      var confirmarContraseña = document.getElementById("new_password2").value;
      
      if (contraseña !== confirmarContraseña) {
          alert("Las contraseñas no coinciden. Por favor, inténtalo de nuevo.");
          return false;
      }
      
      return true; 
  }