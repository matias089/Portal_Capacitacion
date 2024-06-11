<?php

// Inicia la sesión si no está iniciada
session_start();
// Incluye el contenido del navbar
include 'navbar.php';
include 'check_password.php';
// Verifica si el usuario está logueado
if (!isset($_SESSION['tipo_usuario'])) {
    // Si el usuario no está logueado, redirige a la página de login
    header("Location: portada.html");
    exit(); // Es importante salir del script después de redirigir
}

if (isset($_GET['mensaje'])) {
  // Inicializar la variable para almacenar el mensaje HTML
  $mensaje_html = "";

  // Construir el mensaje de alerta según el caso
  switch ($_GET['mensaje']) {
      case "exito":
          $mensaje_html = "<div class='alert alert-success' role='alert'>Los cambios se han realizado exitosamente.</div>";
          break;
      case "no_curso":
          $mensaje_html = "<div class='alert alert-danger' role='alert'>No se ha seleccionado un curso.</div>";
          break;
      case "no_ruts":
          $mensaje_html = "<div class='alert alert-danger' role='alert'>No se han seleccionado ruts.</div>";
          break;
      case "no_enviado":
          $mensaje_html = "<div class='alert alert-danger' role='alert'>No se recibieron datos del formulario.</div>";
          break;
      default:
          $mensaje_html = "<div class='alert alert-danger' role='alert'>Ha ocurrido un error desconocido.</div>";
          break;
  }

  // Mostrar el mensaje HTML en la página
  echo $mensaje_html;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Administrar</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<style>
  .dropdown-toggle {
    overflow: hidden; /* Ocultar el desbordamiento */
    text-overflow: ellipsis; /* Agregar puntos suspensivos para indicar desbordamiento */
    white-space: nowrap; /* Evitar el retorno de línea */
    max-width: 15vw; /* Ancho máximo del botón */
  }
  .dropdown-menu {
    max-height: 200px;
    overflow-y: auto;
  }
  .hidden {
  display: none !important;
}
/* Ajustes para el contenedor principal */
.container {
    margin-top: 5vh;
    max-height: 65vh; /* Altura máxima del contenedor */
    overflow-y: auto; /* Agregar una barra de desplazamiento vertical si el contenido excede la altura máxima */
}
</style>
</head>
<body>

<div class="container">
<h2 style="text-align:center; margin-bottom:3vh;">Asignación de cursos</h2>
  <div class="row">
    <div class="col-md-3"> <!-- Columna para la lista de cursos -->
      <form action="asignar_curso.php" method="post" style="margin-top: 25px;">        
        <div class="btn-group-vertical"> <!-- Alinear verticalmente el contenido -->
          <button id="dropdownButton" class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Lista de cursos
          </button>
          <input type="hidden" id="cursoSeleccionado" name="curso" value=""> <!-- Cambiar el nombre del campo oculto a "curso" -->
            <ul class="dropdown-menu">
            <?php
            include 'db/db.php';
            $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
            if (!$conn) {
              echo "Error de conexión.";
              exit;
            }
            $consulta_cursos = pg_query($conn, "SELECT nombre_cur FROM cursos ORDER BY nombre_cur ASC");
            if (!$consulta_cursos) {
              echo "Error de consulta.";
              exit;
            }
            while ($fila_curso = pg_fetch_assoc($consulta_cursos)) {
              echo "<li><a class='dropdown-item curso-item' href='#' data-curso='" . $fila_curso['nombre_cur'] . "'>" . $fila_curso['nombre_cur'] . "</a></li>";
            }
            pg_free_result($consulta_cursos);
            pg_close($conn);
            ?>
            </ul>
        </div>
    </div>
    <div class="col-md-9"> <!-- Ancho de la tabla en dispositivos medianos -->
      <div class="table-responsive" style="min-height: 50vh;"> <!-- Hacer la tabla responsive -->
      <?php
          // Establece el número de registros por página
          $registrosPorPagina = 10;

          // Conexión a la base de datos
          $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
          if (!$conn) {
              echo "Error de conexión.";
              exit;
          }

          // Consulta SQL para recuperar el número total de registros
          $totalRegistrosConsulta = pg_query($conn, "SELECT COUNT(*) AS total FROM usuarios");
          $totalRegistros = pg_fetch_assoc($totalRegistrosConsulta)['total'];

          // Calcula el número total de páginas
          $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

          // Obtiene el número de página actual
          $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

          // Calcula el offset para la consulta SQL
          $offset = ($paginaActual - 1) * $registrosPorPagina;

          // Consulta SQL para recuperar los registros de la página actual
          //$consulta = pg_query($conn, "SELECT empresa, area, cargo, rut, nombre FROM usuarios LIMIT $registrosPorPagina OFFSET $offset");
          $consulta = pg_query($conn, "SELECT empresa, area, cargo, rut, nombre FROM usuarios");

          // Imprime la tabla con los registros de la página actual
          ?>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" id="empresaColumn">
                    
                    <!-- Dropdown para filtrar por empresa -->
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownEmpresa" data-bs-toggle="dropdown" aria-expanded="false">
                        Empresa
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownEmpresa">
                        <?php
                        // Consulta SQL para recuperar los valores únicos de empresa
                        $consulta_empresas = pg_query($conn, "SELECT DISTINCT empresa FROM usuarios");
                        if (!$consulta_empresas) {
                          echo "Error de consulta.";
                          exit;
                        }

                        // Iterar sobre los resultados y poblar el dropdown
                        while ($fila_empresa = pg_fetch_assoc($consulta_empresas)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_empresa['empresa'] . "</a></li>";
                        }

                        // Liberar el resultado
                        pg_free_result($consulta_empresas);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="areaColumn">
                    
                    <!-- Dropdown para filtrar por área -->
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownArea" data-bs-toggle="dropdown" aria-expanded="false">
                        Área
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownArea">
                        <?php
                        // Consulta SQL para recuperar los valores únicos de área
                        $consulta_areas = pg_query($conn, "SELECT DISTINCT area FROM usuarios");
                        if (!$consulta_areas) {
                          echo "Error de consulta.";
                          exit;
                        }

                        // Iterar sobre los resultados y poblar el dropdown
                        while ($fila_area = pg_fetch_assoc($consulta_areas)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_area['area'] . "</a></li>";
                        }

                        // Liberar el resultado
                        pg_free_result($consulta_areas);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="cargoColumn">
                    
                    <!-- Dropdown para filtrar por cargo -->
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownCargo" data-bs-toggle="dropdown" aria-expanded="false">
                        Cargo
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownCargo">
                        <?php
                        // Consulta SQL para recuperar los valores únicos de cargo
                        $consulta_cargos = pg_query($conn, "SELECT DISTINCT cargo FROM usuarios");
                        if (!$consulta_cargos) {
                          echo "Error de consulta.";
                          exit;
                        }

                        // Iterar sobre los resultados y poblar el dropdown
                        while ($fila_cargo = pg_fetch_assoc($consulta_cargos)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_cargo['cargo'] . "</a></li>";
                        }

                        // Liberar el resultado
                        pg_free_result($consulta_cargos);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="rutColumn">
                    
                    <!-- Dropdown para filtrar por RUT -->
                    <div class="dropdown">
                      <button class="btn btn-secondary" type="button" id="dropdownRut" aria-expanded="false" style="width: 5vw;">
                        RUT
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownRut">
                        <?php
                        // Consulta SQL para recuperar los valores únicos de RUT
                        $consulta_ruts = pg_query($conn, "SELECT DISTINCT rut FROM usuarios");
                        if (!$consulta_ruts) {
                          echo "Error de consulta.";
                          exit;
                        }

                        // Iterar sobre los resultados y poblar el dropdown
                        while ($fila_rut = pg_fetch_assoc($consulta_ruts)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_rut['rut'] . "</a></li>";
                        }

                        // Liberar el resultado
                        pg_free_result($consulta_ruts);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="nombreColumn">
                    
                    <!-- Dropdown para filtrar por nombre -->
                    <div class="dropdown">
                      <button class="btn btn-secondary" type="button" id="dropdownNombre" aria-expanded="false">
                        Nombre
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownNombre">
                        <?php
                        // Consulta SQL para recuperar los valores únicos de nombre
                        $consulta_nombres = pg_query($conn, "SELECT DISTINCT nombre FROM usuarios");
                        if (!$consulta_nombres) {
                          echo "Error de consulta.";
                          exit;
                        }

                        // Iterar sobre los resultados y poblar el dropdown
                        while ($fila_nombre = pg_fetch_assoc($consulta_nombres)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_nombre['nombre'] . "</a></li>";
                        }

                        // Liberar el resultado
                        pg_free_result($consulta_nombres);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col">
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="select-all-checkbox">
                        <label class="form-check-label" for="select-all-checkbox">Seleccionar todo</label>
                      </div>
                    <button class="btn btn-secondary" type="button" id="dropdownNombre" aria-expanded="false">
                        Seleccionado(s)
                    </button></th>
                </tr>
              </thead>
              <tbody id="tablaUsuarios">
                <?php
                while ($fila = pg_fetch_assoc($consulta)) {
                  echo "<tr>";
                  echo "<td>" . $fila['empresa'] . "</td>";
                  echo "<td>" . $fila['area'] . "</td>";
                  echo "<td>" . $fila['cargo'] . "</td>";
                  echo "<td>" . $fila['rut'] . "</td>";
                  echo "<td>" . $fila['nombre'] . "</td>";
                  echo "<td><input type='checkbox' name='ruts_seleccionados[]' value='" . $fila['rut'] . "'></td>"; // Agregar la celda con el checkbox
                  echo "</tr>";
                }
                ?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan="5" style="text-align: center;">
                    <button id="btnCancelar" class="btn btn-secondary">Cancelar</button>
                    <button id="btnGuardar" class="btn btn-primary">Guardar</button>
                  </td>
                </tr>
              </tfoot>
            </table>
          </form>

          <!--<nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
              <li class="page-item <?php // echo ($paginaActual == 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?pagina=<?php // echo $paginaActual - 1; ?>">Previous</a>
              </li>
              <?php // for ($i = 1; $i <= $totalPaginas; $i++) : ?>
                <li class="page-item <?php // echo ($paginaActual == $i) ? 'active' : ''; ?>">
                  <a class="page-link" href="?pagina=<?php // echo $i; ?>"><?php // echo $i; ?></a>
                </li>
              <?php // endfor; ?>
              <li class="page-item <?php // echo ($paginaActual == $totalPaginas) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?pagina=<?php // echo $paginaActual + 1; ?>">Next</a>
              </li>
            </ul>
          </nav>-->

          <?php
          pg_free_result($consulta);
          pg_close($conn);
          ?>

      </div>
    </div>
  </div>
</div>

<script src="/Portal_Capacitacion/templates/js/administrar.js">
  </script>  

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>