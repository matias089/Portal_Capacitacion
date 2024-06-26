<?php

session_start();
include 'navbar.php';
include 'check_password.php';
include 'error_control.php';
if (!isset($_SESSION['tipo_usuario'])) {
    header("Location: portada.html");
    exit();
}

if (isset($_GET['mensaje'])) {
  $mensaje_html = "";

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
    overflow: hidden;
    text-overflow: ellipsis; 
    white-space: nowrap; 
    max-width: 15vw; 
  }
  .dropdown-menu {
    max-height: 200px;
    overflow-y: auto;
  }
  .hidden {
  display: none !important;
}

.container {
    margin-top: 5vh;
    max-height: 65vh;
    overflow-y: auto; 
}
</style>
</head>
<body>

<div class="container">
<h2 style="text-align:center; margin-bottom:3vh;">Asignación de cursos</h2>
  <div class="row">
    <div class="col-md-3">
      <form action="asignar_curso.php" method="post" style="margin-top: 25px;">        
        <div class="btn-group-vertical">
          <button id="dropdownButton" class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            Lista de cursos
          </button>
          <input type="hidden" id="cursoSeleccionado" name="curso" value="">
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
    <div class="col-md-9">
      <div class="table-responsive" style="min-height: 50vh;">
      <?php
          $registrosPorPagina = 10;

          $conn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$password");
          if (!$conn) {
              echo "Error de conexión.";
              exit;
          }

          $totalRegistrosConsulta = pg_query($conn, "SELECT COUNT(*) AS total FROM usuarios");
          $totalRegistros = pg_fetch_assoc($totalRegistrosConsulta)['total'];

          $totalPaginas = ceil($totalRegistros / $registrosPorPagina);

          $paginaActual = isset($_GET['pagina']) ? $_GET['pagina'] : 1;

          $offset = ($paginaActual - 1) * $registrosPorPagina;

          $consulta = pg_query($conn, "SELECT empresa, area, cargo, rut, nombre FROM usuarios");

          ?>
            <table class="table">
              <thead>
                <tr>
                  <th scope="col" id="empresaColumn">
                    
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownEmpresa" data-bs-toggle="dropdown" aria-expanded="false">
                        Empresa
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownEmpresa">
                        <?php
                        $consulta_empresas = pg_query($conn, "SELECT DISTINCT empresa FROM usuarios");
                        if (!$consulta_empresas) {
                          echo "Error de consulta.";
                          exit;
                        }
                        while ($fila_empresa = pg_fetch_assoc($consulta_empresas)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_empresa['empresa'] . "</a></li>";
                        }
                        pg_free_result($consulta_empresas);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="areaColumn">
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownArea" data-bs-toggle="dropdown" aria-expanded="false">
                        Área
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownArea">
                        <?php
                        $consulta_areas = pg_query($conn, "SELECT DISTINCT area FROM usuarios");
                        if (!$consulta_areas) {
                          echo "Error de consulta.";
                          exit;
                        }
                        while ($fila_area = pg_fetch_assoc($consulta_areas)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_area['area'] . "</a></li>";
                        }
                        pg_free_result($consulta_areas);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="cargoColumn">
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownCargo" data-bs-toggle="dropdown" aria-expanded="false">
                        Cargo
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownCargo">
                        <?php
                        $consulta_cargos = pg_query($conn, "SELECT DISTINCT cargo FROM usuarios");
                        if (!$consulta_cargos) {
                          echo "Error de consulta.";
                          exit;
                        }
                        while ($fila_cargo = pg_fetch_assoc($consulta_cargos)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_cargo['cargo'] . "</a></li>";
                        }
                        pg_free_result($consulta_cargos);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="rutColumn">
                    <div class="dropdown">
                      <button class="btn btn-secondary" type="button" id="dropdownRut" aria-expanded="false" style="width: 5vw;">
                        RUT
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownRut">
                        <?php
                        $consulta_ruts = pg_query($conn, "SELECT DISTINCT rut FROM usuarios");
                        if (!$consulta_ruts) {
                          echo "Error de consulta.";
                          exit;
                        }
                        while ($fila_rut = pg_fetch_assoc($consulta_ruts)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_rut['rut'] . "</a></li>";
                        }
                        pg_free_result($consulta_ruts);
                        ?>
                      </ul>
                    </div>
                  </th>
                  <th scope="col" id="nombreColumn">
                    <div class="dropdown">
                      <button class="btn btn-secondary" type="button" id="dropdownNombre" aria-expanded="false">
                        Nombre
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownNombre">
                        <?php
                        $consulta_nombres = pg_query($conn, "SELECT DISTINCT nombre FROM usuarios");
                        if (!$consulta_nombres) {
                          echo "Error de consulta.";
                          exit;
                        }
                        while ($fila_nombre = pg_fetch_assoc($consulta_nombres)) {
                          echo "<li><a class='dropdown-item' href='#'>" . $fila_nombre['nombre'] . "</a></li>";
                        }
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