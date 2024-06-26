<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña</title>
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/pwreset.css">
</head>
<body>
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
                    <h2>Recuperar contraseña</h2>
                    <p>Sin puntos y con guión</p>
                    <form id="forgotPasswordForm" action="envio_correo.php" method="post">
                        <div class="inputBox">
                            <input type="text" id="rut" name="rut" placeholder="Ingrese su RUT" required autocomplete="off"/>
                        </div>
                        <div class="inputBox">
                            <input type="submit" value="Enviar" />
                        </div>
                        <p class="forget">
                            ¿Ya tienes una cuenta? <a href="/Portal_Capacitacion/login.php">Iniciar sesión</a>
                        </p>
                    </form>
                </div>
             </div>
        </div>
    </section>
</body>
</html>
