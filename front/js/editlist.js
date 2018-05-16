var appTaskEdit = {

    init: function () {

        console.log('appinit edittask');

        $('.edition').on('click', appTaskEdit.update);
        $('.delete').on('click', appTaskEdit.delete);
    },

    update: function (evt) {

        evt.preventDefault();
        var taskId = $(this).attr('id');
        var $form = $('#form-' + taskId);
        var $data = $form.serialize();

        $.ajax({
                url: routeEditTask,
                method:'POST',
                data: $data,
                dataType:'json',
            }
        ).done( function(data) {
            if(data.success === true) {
                $('#tache-' + taskId).children().first().children().first().
                text('A '+ data.time +' - '+ data.title);
                $('#pills-affichage-calendar-'+ taskId).children().first().
                text(data.message);
                $('#pills-edition-calendar-'+ taskId).
                collapse('hide');
                $('#modal-edit-success').modal('show');
            }
            else {
                $('#modal-edit-danger').modal('show');
            }

        }).fail(function() {
            $('#modal-edit-danger').modal('show');
        });

    },

    delete: function (e) {
        var idTask= $(this).attr('id');
        var listItem = $('#tache-' + idTask);

        $.ajax(

            deleteUrl,
            {
                method: 'POST',
                data: {
                    'idTask': idTask,
                }
            }
            // Ecouteur du retour de la requête en cas de succès
        ).done(function(data) {
            // data correspond au contenu renvoyé par la réponse
            if(data.success == true){
                //$('.list-group-item[data-id=' + data.id +']').remove();
                $(listItem).remove();
            }

            })
        .fail(function() {
            console.log('connexion failed');
        });
    },
};

$(appTaskEdit.init);
