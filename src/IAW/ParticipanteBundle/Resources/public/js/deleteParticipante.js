$(document).ready(function(){
    $('.btn-delete').click(function(e){
        e.preventDefault();

        var row = $(this).parents('tr');
        var id = row.data('id');


        var form = $('#form-delete');

        var url = form.attr('action').replace(':PARTICIPANTE_ID', id);

        var data = form.serialize();


        bootbox.confirm("¿Desea eliminar?", function(result){
          if(result == true){

            $('#delete-progress').removeClass('hidden');

            $.post(url, data, function(res){

                $('#delete-progress').addClass('hidden');

                  //Estoy eliminando el participante
                  row.fadeOut();
                  //Hago aparecer el mensaje
                  $('#participante-message').text(res.message);
                  $('#message').removeClass('hidden');

                  //Luego lo hago desvanecer
                  setTimeout(function () {
                      $('#message').addClass('visuallyhidden');
                      $('#message').one('transitionend', function(e) {
                      $('#message').addClass('hidden');
                    });
                  }, 1000);
                  $('#message').removeClass('visuallyhidden');


            }).fail(function(){
              alert('ERROR');
              row.show();
            });
          }
        });

    });
});
