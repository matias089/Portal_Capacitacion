<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test de Selección Múltiple</title>
    <link rel="stylesheet" href="/Portal_Capacitacion/templates/css/prueba.css">
</head>
<body>
    <h1>Test de Selección Múltiple</h1>
    <form action="calcular_resultado.php" method="post">
        <ol>
            <?php
            $preguntas = array(
                "¿Cuál es la capital de Australia?",
                "¿Quién escribió 'El Principito'?",
                "¿Cuál es el símbolo químico del hierro?",
                "¿En qué año terminó la Segunda Guerra Mundial?",
                "¿Quién pintó 'La noche estrellada'?",
                "¿Cuál es el animal terrestre más grande del mundo?",
                "¿En qué año se fundó Google?",
                "¿Cuál es el país más grande del mundo por territorio?",
                "¿Quién escribió 'Orgullo y Prejuicio'?",
                "¿Cuál es el planeta más grande del sistema solar?",
                "¿Quién descubrió América?"
            );

            $respuestas = array(
                array("Sydney", "Canberra", "Melbourne", "Brisbane"),
                array("Antoine de Saint-Exupéry", "Victor Hugo", "J.K. Rowling", "Gabriel García Márquez"),
                array("Ir", "Fe", "Au", "Ag"),
                array("1942", "1945", "1950", "1939"),
                array("Pablo Picasso", "Vincent van Gogh", "Claude Monet", "Salvador Dalí"),
                array("Elefante", "Ballena azul", "Jirafa", "Cocodrilo"),
                array("1995", "2000", "1998", "2005"),
                array("China", "Canadá", "Rusia", "Estados Unidos"),
                array("Jane Austen", "Charles Dickens", "Emily Brontë", "Leo Tolstoy"),
                array("Tierra", "Marte", "Júpiter", "Saturno"),
                array("Cristóbal Colón", "Vasco de Gama", "Magallanes", "Hernán Cortés")
            );
            
            for ($i = 0; $i < count($preguntas); $i++) {
                echo "<li>" . $preguntas[$i] . "<br>";
                foreach ($respuestas[$i] as $opcion) {
                    echo "<label><input type='radio' name='respuesta$i' value='$opcion'>$opcion</label><br>";
                }
                echo "</li>";
            }
            ?>
        </ol>
        <input type="submit" value="Enviar respuestas">
    </form>
</body>
</html>