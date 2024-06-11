    // Cuando el carrusel cambia de diapositiva
    $('#carouselExampleSlidesOnly').on('slid.bs.carousel', function () {
        // Detener todos los videos en el carrusel
        $('video').each(function () {
          $(this).get(0).pause();
        });
  
        // Obtener el video de la diapositiva activa y reproducirlo
        var video = $('.carousel-item.active').find('video').get(0);
        if (video) {
          video.play();
        }
      });
  
      // Reproducir el primer video cuando se carga la página
      $(document).ready(function () {
        $('.carousel-item:first-child video').get(0).play();
      });
  
  
      //INACTIVIDAD
      let timer = null;
      let inactiveTime = 10000; // 20 segundos
    
      $(document).ready(function() {
        timer = setTimeout(function() {
          alert("ADVERTENCIA!!!\n Estas a punto de cerrar sesion por inactividad.");
          // Aquí puedes agregar la lógica para cerrar la sesión
        }, inactiveTime);
    
        $(document).on("mousemove keydown", function() {
          clearTimeout(timer);
          timer = setTimeout(function() {
            alert("ADVERTENCIA!!!\n Estás a punto de cerrar sesión por inactividad.");
            // Aquí puedes agregar la lógica para cerrar la sesión
          }, inactiveTime);
        });
      });