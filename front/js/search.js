var appSearch = {

    init: function () {

        console.log('appSearch');

        $('#search').on('keyup',appSearch.search);

    },

    search: function (evt) {
        evt.preventDefault();
       $('#result').html('');
        var label=$(this).val();


       if( $(this).val().length >= 0){
           $.ajax({
                   url: productSearch,
                   method:'POST',
                   data: {'label': label}
               }
           ).done( function(back) {
               if(back === false){
                   $('#result').addClass('alert alert-danger').html('votre recherche ne retourne aucun resultat !');
                   $('.product').hide();
               }
               else {
                   $('.product').hide();
                   $('#result').removeClass('alert alert-danger');
                   $(back).each(function () {
                       var id = this.id;
                       $('#' + id).show();
                   });
               }
           }).fail(function() {
               console.log('connexion failed');

           });
       }
       else{
           $('.product').show();
       }

    }



};

$(appSearch.init);