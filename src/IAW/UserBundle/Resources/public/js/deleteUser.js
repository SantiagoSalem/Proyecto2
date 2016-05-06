$(document).ready(function(){
    $('.btn-delete').click(function(e){
        e.preventDefault();

        var row = $(this).parents('tr');
        var id = row.data('id');

        //alert(id);

        var form = $('#form-delete');

        var url = form.attr('action').replace(':USER_ID', id);

        var data = form.serialize();

        //alert(data);

        bootbox.confirm("¿Desea eliminar?", function(result){
          if(result == true){

            $('#delete-progress').removeClass('hidden');

            $.post(url, data, function(res){

                $('#delete-progress').addClass('hidden');

                if(res.removed == 1){
                  //Estoy eliminando el usuario
                  row.fadeOut();
                  //Hago aparecer el mensaje
                  $('#user-message').text(res.message);
                  $('#message').removeClass('hidden');

                  //Luego lo hago desvanecer
                  setTimeout(function () {
                      $('#message').addClass('visuallyhidden');
                      $('#message').one('transitionend', function(e) {
                      $('#message').addClass('hidden');
                    });
                  }, 1000);
                  $('#message').removeClass('visuallyhidden');

                }
                else{
                  $('#user-message-danger').text(res.message);
                  $('#message-danger').removeClass('hidden');

                  //Luego lo hago desvanecer
                  setTimeout(function () {
                      $('#message-danger').addClass('visuallyhidden');
                      $('#message-danger').one('transitionend', function(e) {
                      $('#message-danger').addClass('hidden');
                    });
                  }, 1000);
                  $('#message-danger').removeClass('visuallyhidden');


                }
            }).fail(function(){
              alert('ERROR');
              row.show();
            });
          }
        });

    });
});
