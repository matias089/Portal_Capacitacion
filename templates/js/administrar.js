
  document.addEventListener("DOMContentLoaded", function() {
    var cursoItems = document.querySelectorAll('.curso-item');

    cursoItems.forEach(function(item) {
      item.addEventListener('click', function() {
        var cursoSeleccionado = this.getAttribute('data-curso');

        document.getElementById('cursoSeleccionado').value = cursoSeleccionado;

        document.getElementById('dropdownButton').innerText = cursoSeleccionado;
      });
    });
  });



$(document).ready(function(){
    $('#select-all-checkbox').change(function() {
      var isChecked = $(this).is(':checked');
      $('#tablaUsuarios tr:visible input[type="checkbox"]').prop('checked', isChecked);
  });
  
    $('.dropdown-menu .dropdown-item').click(function(){
      var columna = $(this).closest('.dropdown').find('button').text().trim();
      var valor = $(this).text().trim();
      var columnIndex = $(this).closest('th').index(); 
  
      $('#tablaUsuarios tr').removeClass('hidden'); 
  
      if (valor !== 'TODOS') {
        $('#tablaUsuarios tr').each(function(){
          var texto = $(this).find('td:eq('+ columnIndex +')').text().trim();
          if (texto !== valor) {
            $(this).addClass('hidden');
          }
        });
      }
    });

    $('#btnGuardar').click(function() {
      var rutsSeleccionados = []; 

      $('#tablaUsuarios tbody tr').each(function() {
        var checkbox = $(this).find('input[type="checkbox"]');

        if (checkbox.prop('checked')) {
          var rut = $(this).find('td:eq(3)').text();
          rutsSeleccionados.push(rut);
        }
      });
      console.log('RUTs seleccionados:', rutsSeleccionados);
    });
  
  });

  $(document).ready(function() {
    $('.curso-item').click(function() {
      var cursoSeleccionado = $(this).data('curso');
      $('#cursoSeleccionado').val(cursoSeleccionado);
      $('#dropdownButton').text(cursoSeleccionado);
    });
  });