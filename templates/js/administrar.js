  // Script para manejar la selección del curso
  document.addEventListener("DOMContentLoaded", function() {
    // Obtener todos los elementos de clase 'curso-item'
    var cursoItems = document.querySelectorAll('.curso-item');

    // Agregar un evento 'click' a cada elemento
    cursoItems.forEach(function(item) {
      item.addEventListener('click', function() {
        // Obtener el valor del atributo 'data-curso'
        var cursoSeleccionado = this.getAttribute('data-curso');

        // Actualizar el valor del campo oculto 'cursoSeleccionado'
        document.getElementById('cursoSeleccionado').value = cursoSeleccionado;

        // Cambiar el texto del botón principal al curso seleccionado
        document.getElementById('dropdownButton').innerText = cursoSeleccionado;
      });
    });
  });



$(document).ready(function(){
    // Evento de cambio para el checkbox "Seleccionar todo"
    $('#select-all-checkbox').change(function() {
      var isChecked = $(this).is(':checked');
      $('#tablaUsuarios tr:visible input[type="checkbox"]').prop('checked', isChecked);
  });
  
  
    // Función para filtrar la tabla según el valor seleccionado en los dropdowns
    $('.dropdown-menu .dropdown-item').click(function(){
      var columna = $(this).closest('.dropdown').find('button').text().trim();
      var valor = $(this).text().trim();
      var columnIndex = $(this).closest('th').index(); // Obtener el índice de la columna
  
      $('#tablaUsuarios tr').removeClass('hidden'); // Eliminar la clase oculta de todas las filas
  
      if (valor !== 'TODOS') {
        $('#tablaUsuarios tr').each(function(){
          var texto = $(this).find('td:eq('+ columnIndex +')').text().trim(); // Usar el índice de la columna
          if (texto !== valor) {
            $(this).addClass('hidden'); // Agregar la clase oculta a las filas que no coincidan con el filtro
          }
        });
      }
    });
  
    // Evento clic para el botón "Guardar"
    $('#btnGuardar').click(function() {
      var rutsSeleccionados = []; // Array para almacenar los RUTs seleccionados
  
      // Iterar sobre las filas de la tabla
      $('#tablaUsuarios tbody tr').each(function() {
        // Obtener el checkbox de cada fila
        var checkbox = $(this).find('input[type="checkbox"]');
        
        // Verificar si el checkbox está marcado
        if (checkbox.prop('checked')) {
          // Obtener el RUT de la fila actual y agregarlo al array
          var rut = $(this).find('td:eq(3)').text(); // Cambia el índice si es necesario
          rutsSeleccionados.push(rut);
        }
      });
  
      // Log para depurar
      console.log('RUTs seleccionados:', rutsSeleccionados);
  
      // Aquí puedes realizar cualquier acción con los RUTs seleccionados, como enviarlos a través de una solicitud AJAX o mostrarlos en la consola
    });
  
  });

  $(document).ready(function() {
    $('.curso-item').click(function() {
      var cursoSeleccionado = $(this).data('curso');
      $('#cursoSeleccionado').val(cursoSeleccionado);
      $('#dropdownButton').text(cursoSeleccionado); // Opcional: Actualiza el texto del botón de cursos con el curso seleccionado
    });
  });