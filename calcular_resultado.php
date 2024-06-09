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
        // Definir las respuestas correctas
        $respuestas_correctas = array(
            "Canberra", // 1
            "Antoine de Saint-Exupéry", // 2
            "Fe", // 3
            "1945", // 4
            "Vincent van Gogh", // 5
            "Elefante", // 6
            "1998", // 7
            "Rusia", // 8
            "Jane Austen", // 9
            "Júpiter", // 10
            "Cristóbal Colón" // 11
        );

        // Inicializar contador de respuestas correctas
        $respuestas_correctas_count = 0;

        // Verificar respuestas enviadas
        for ($i = 0; $i < count($respuestas_correctas); $i++) {
            $respuesta_enviada = $_POST["respuesta$i"];
            if ($respuesta_enviada === $respuestas_correctas[$i]) {
                $respuestas_correctas_count++;
            }
        }

        // Calcular puntaje
        $puntaje = $respuestas_correctas_count / count($respuestas_correctas) * 100;

        // Mostrar resultados
        echo "<p>Has respondido correctamente $respuestas_correctas_count de " . count($respuestas_correctas) . " preguntas.</p>";
        echo "<p>Tu puntaje es: $puntaje%</p>";
        ?>
    </div>
</body>
</html>
