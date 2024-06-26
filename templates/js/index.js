
    $('#carouselExampleSlidesOnly').on('slid.bs.carousel', function () {
        $('video').each(function () {
          $(this).get(0).pause();
        });
        var video = $('.carousel-item.active').find('video').get(0);
        if (video) {
          video.play();
        }
      });
      $(document).ready(function () {
        $('.carousel-item:first-child video').get(0).play();
      });
  
      let timer = null;
      let inactiveTime = 200000;
    
      $(document).ready(function() {
        timer = setTimeout(function() {
          alert("ADVERTENCIA!!!\n Estas a punto de cerrar sesion por inactividad.");
        }, inactiveTime);
    
        $(document).on("mousemove keydown", function() {
          clearTimeout(timer);
          timer = setTimeout(function() {
            alert("ADVERTENCIA!!!\n Estás a punto de cerrar sesión por inactividad.");
          }, inactiveTime);
        });
      });