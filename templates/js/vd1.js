document.getElementById("realizarExamenButton").addEventListener("click", function() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "templates/preguntas/actualizar_estado.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                console.log("Estado actualizado correctamente");
            } else {
                console.error("Error al actualizar el estado:", xhr.statusText);
            }
        }
    };
    xhr.send("rut=<?php echo $rut_del_usuario; ?>");
});