<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado del Test</title>
</head>
<body>
    <h1>Resultado del Test</h1>
    <div>
        <?php
        $respuestas_correctas = array(
            " "
        );
        $respuestas_correctas_count = 0;

        for ($i = 0; $i < count($respuestas_correctas); $i++) {
            $respuesta_enviada = $_POST["respuesta$i"];
            if ($respuesta_enviada === $respuestas_correctas[$i]) {
                $respuestas_correctas_count++;
            }
        }

        $puntaje = $respuestas_correctas_count / count($respuestas_correctas) * 100;
        echo "<p>Has respondido correctamente $respuestas_correctas_count de " . count($respuestas_correctas) . " preguntas.</p>";
        echo "<p>Tu puntaje es: $puntaje%</p>";
        ?>
    </div>
</body>
</html>
